<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../services/AuthService.php';
require_once __DIR__ . '/../services/ReporteService.php';
require_once __DIR__ . '/../models/Usuario.php';

/**
 Pruebas de integracion - Procesos completos
 * CommunityFix
 */
class IntegrationTest extends TestCase
{
    private AuthService $authService;
    private ReporteService $reporteService;
    private Usuario $usuarioModel;

    protected function setUp(): void
    {
        $this->authService    = new AuthService();
        $this->reporteService = new ReporteService();
        $this->usuarioModel   = new Usuario();
    }

    private function correoUnico(string $prefijo): string
    {
        return $prefijo . '_' . time() . '_' . rand(1000, 9999) . '@communityfix.com';
    }

    /**
     * TI-01: Proceso completo de Registro + Login
     */
     
    public function testRegistroSeguidoDeLoginProcesoCompleto(): void
    {
        $nombre     = 'Ciudadano Integracion';
        $correo     = $this->correoUnico('pi01');
        $contrasena = 'ClaveSegura123';

        $registroOk = $this->authService->registro($nombre, $correo, $contrasena);
        $this->assertTrue($registroOk, 'El registro inicial deberia completarse correctamente');

        $usuarioGuardado = $this->usuarioModel->buscarPorCorreo($correo);
        $this->assertNotFalse($usuarioGuardado, 'El usuario debe quedar persistido en la base de datos');
        $this->assertSame(2, (int)$usuarioGuardado['id_rol'], 'Todo registro publico debe crearse con el rol ciudadano');
        $this->assertNotSame($contrasena, $usuarioGuardado['contrasena'], 'La contrasena nunca debe guardarse en texto plano');
        $this->assertTrue(password_verify($contrasena, $usuarioGuardado['contrasena']), 'El hash guardado debe corresponder a la contrasena original');

        $loginOk = $this->authService->login($correo, $contrasena);
        $this->assertTrue($loginOk, 'El login con las credenciales recien registradas debe ser exitoso');
    }

    /**
     * TI-02: Proceso completo de Autenticacion + Creacion de Reporte + Verificacion en listado
     */
    public function testFlujoCompletoDeCreacionDeReportePorUsuarioAutenticado(): void
    {
        $correo     = $this->correoUnico('pi02');
        $contrasena = 'ReporteClave1';
        $this->authService->registro('Ciudadano Reportero', $correo, $contrasena);
        $this->assertTrue($this->authService->login($correo, $contrasena), 'Precondicion: el login debe ser exitoso');

        $usuario = $this->usuarioModel->buscarPorCorreo($correo);
        $idUsuario = (int)$usuario['id_usuario'];

        $categorias = $this->reporteService->obtenerCategorias();
        $this->assertNotEmpty($categorias, 'El catalogo de categorias no deberia estar vacio');
        $idCategoria = (int)$categorias[0]['id_categoria'];

        $creado = $this->reporteService->crear(
            $idUsuario,
            'Fuga de agua en la calle principal',
            'Se observa una fuga de agua constante frente al parque central',
            'Calle Principal esq. Duarte',
            $idCategoria
        );
        $this->assertTrue($creado, 'La creacion del reporte con datos validos debe ser exitosa');

        $misReportes = $this->reporteService->listarPorUsuario($idUsuario);
        $this->assertCount(1, $misReportes, 'El usuario debe tener exactamente un reporte registrado');
        $this->assertSame('Fuga de agua en la calle principal', $misReportes[0]['titulo']);
        $this->assertSame('pendiente', $misReportes[0]['nombre_estado'], 'Todo reporte nuevo debe iniciar en estado pendiente');
    }

    /**
     * TI-03: Proceso completo de Rechazo por Datos Incompletos
     */
     public function testCreacionDeReporteConDatosIncompletosEsRechazada(): void
    {
        $correo     = $this->correoUnico('pi03');
        $contrasena = 'DatosIncompletos1';
        $this->authService->registro('Ciudadano Incompleto', $correo, $contrasena);
        $this->authService->login($correo, $contrasena);

        $usuario = $this->usuarioModel->buscarPorCorreo($correo);
        $idUsuario = (int)$usuario['id_usuario'];

        $resultado = $this->reporteService->crear(
            $idUsuario,
            '',
            'Descripcion sin titulo',
            'Ubicacion de prueba',
            0
        );
        $this->assertFalse($resultado, 'La creacion sin titulo y sin categoria valida debe ser rechazada');

        $misReportes = $this->reporteService->listarPorUsuario($idUsuario);
        $this->assertCount(0, $misReportes, 'No debe quedar ningun registro huerfano en la base de datos');
    }

    /**
     * TI-04: Proceso completo de Aislamiento de Datos entre Usuarios
     */
    public function testAislamientoDeReportesEntreDosUsuarios(): void
    {
        $correoA = $this->correoUnico('pi04a');
        $correoB = $this->correoUnico('pi04b');
        $clave   = 'ClaveCompartida1';

        $this->authService->registro('Usuario A', $correoA, $clave);
        $this->authService->registro('Usuario B', $correoB, $clave);

        $usuarioA = $this->usuarioModel->buscarPorCorreo($correoA);
        $usuarioB = $this->usuarioModel->buscarPorCorreo($correoB);
        $idA = (int)$usuarioA['id_usuario'];
        $idB = (int)$usuarioB['id_usuario'];

        $categorias = $this->reporteService->obtenerCategorias();
        $idCategoria = (int)$categorias[0]['id_categoria'];

        $this->reporteService->crear($idA, 'Reporte A1', 'Descripcion A1', 'Ubicacion A1', $idCategoria);
        $this->reporteService->crear($idA, 'Reporte A2', 'Descripcion A2', 'Ubicacion A2', $idCategoria);
        $this->reporteService->crear($idB, 'Reporte B1', 'Descripcion B1', 'Ubicacion B1', $idCategoria);

        $reportesA = $this->reporteService->listarPorUsuario($idA);
        $reportesB = $this->reporteService->listarPorUsuario($idB);

        $this->assertCount(2, $reportesA, 'El usuario A debe ver unicamente sus 2 reportes');
        $this->assertCount(1, $reportesB, 'El usuario B debe ver unicamente su 1 reporte');
    }

    /**
     * TI-05: Proceso completo de Reintento de Login tras Fallo
     */
    public function testReintentoDeLoginTrasCredencialesIncorrectas(): void
    {
        $correo     = $this->correoUnico('pi05');
        $contrasena = 'ClaveCorrecta1';
        $this->authService->registro('Usuario Reintento', $correo, $contrasena);

        $primerIntento = $this->authService->login($correo, 'ClaveIncorrecta1');
        $this->assertFalse($primerIntento, 'El primer intento con clave incorrecta debe fallar');

        $segundoIntento = $this->authService->login($correo, $contrasena);
        $this->assertTrue($segundoIntento, 'El segundo intento con la clave correcta debe ser exitoso');
    }

    /**
     * TI-06: Proceso completo de Consulta del Catalogo de Categorias
     */
    public function testConsultaDeCategoriasContraDatosSemillaDeLaBaseDeDatos(): void
    {
        $categorias = $this->reporteService->obtenerCategorias();

        $this->assertCount(7, $categorias, 'Deben existir las 7 categorias definidas en el script de base de datos');

        $nombres = array_column($categorias, 'nombre_categoria');
        $this->assertContains('Basura acumulada', $nombres);
        $this->assertContains('Alumbrado público', $nombres);
        $this->assertContains('Fugas de agua', $nombres);
    }
}

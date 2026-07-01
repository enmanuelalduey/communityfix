<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../services/AuthService.php';

/**
 * Pruebas unitarias del modulo de Autenticacion
 * CommunityFix - Entregable 4
 */
class AuthServiceTest extends TestCase
{
    private AuthService $authService;
    private string $correoPrueba;

    protected function setUp(): void
    {
        $this->authService = new AuthService();
        $this->correoPrueba = 'test_' . time() . '@communityfix.com';
    }

    /**
     * TC-01: Registro con datos validos
     */
    public function testRegistroConDatosValidosRetornaTrue(): void
    {
        $nombre     = 'Usuario de Prueba';
        $correo     = $this->correoPrueba;
        $contrasena = '123456';

        
        $resultado = $this->authService->registro($nombre, $correo, $contrasena);

        $this->assertTrue($resultado, 'El registro con datos validos deberia retornar true');
    }

    /**
     * TC-02: Registro con correo duplicado
     */
    public function testRegistroConCorreoDuplicadoRetornaFalse(): void
    {
        $nombre     = 'Usuario Original';
        $correo     = $this->correoPrueba;
        $contrasena = '123456';
        $this->authService->registro($nombre, $correo, $contrasena);

        $resultado = $this->authService->registro('Otro Nombre', $correo, 'otraClave');

        $this->assertFalse($resultado, 'No deberia permitir registrar un correo ya existente');
    }

    /**
     * TC-03: Login con credenciales correctas
     */
    public function testLoginConCredencialesCorrectasRetornaTrue(): void
    {
        
    $correo     = 'login_test_' . rand(1000,9999) . '@communityfix.com';
    $contrasena = 'claveSegura123';
    
    $registrado = $this->authService->registro('Usuario Login', $correo, $contrasena);
    
    
    $resultado = $this->authService->login($correo, $contrasena);
    
    
    $this->assertTrue($resultado);
}

    /**
     * TC-04: Login con contraseña incorrecta
     */
    public function testLoginConContrasenaIncorrectaRetornaFalse(): void
    {
   
        $correo = $this->correoPrueba;

        $this->authService->registro('Usuario Test', $correo, 'claveCorrecta');

        $resultado = $this->authService->login($correo, 'claveIncorrecta');

        $this->assertFalse($resultado, 'El login con contrasena incorrecta debe retornar false');
    }

    /**
     * TC-05: Login con correo que no existe
     */
    public function testLoginConCorreoInexistenteRetornaFalse(): void
    {
        $correoInexistente = 'no_existe_' . time() . '@communityfix.com';

        $resultado = $this->authService->login($correoInexistente, 'cualquierClave');

        $this->assertFalse($resultado, 'El login con correo inexistente debe retornar false');
    }
}
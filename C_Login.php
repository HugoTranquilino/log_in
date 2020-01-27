<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_Login extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('m_login');
        
    }

    public function index()
    {
        if($this->session->userdata('logeado') == TRUE){
            $dirección = ($_SESSION['id_direccion']);
            
            if(isset($dirección) && !empty($dirección)){
                if ($dirección == 1) {
                    echo "CONTRALORIA GENERAL";
                } elseif ($dirección == 2) {
                    echo "NORMATIVIDAD Y APOYO TÉCNICO";
                } elseif ($dirección == 3) {
                    echo "RESPONSABILIDADES ADMINISTRATIVAS";
                } elseif ($dirección == 4) {
                    echo "INNOVACIÓN Y MEJORA";
                } elseif ($dirección == 5){
                    echo "ORGANOS INTERNOS SECTORIAL";
                } elseif ($dirección == 6){
                    echo "ORGANOS INTERNOS ALCALDIAS";
                } elseif ($dirección == 7) {
                    // echo "CONTRAORIA CIUDADANA";
                } elseif ($dirección == 8) {
                    echo "ADMINISTRACIÓN Y FINANZAS";
                } elseif ($dirección == 9) {
                    echo "VIGILANCIA MOVIL";
                }
                
                switch ($dirección) {
                    case 1:
                        echo "contraoria general";
                        break;
                    case 2:
                        // echo "apoyo técnico";
                        $this->load->view("admin/tabla");
                        $this->load->view("sitio/V_Barra");
                        // $this->load->view("admin/V_Contra_ciudadana");
                        break;
                    case 3:
                        echo "responsabilidad administrativa";
                        break;
                    case 4:
                        echo "innovación y mejora";
                        break;
                    case 5:
                        echo "organos internos sectorial";
                        break;
                    case 6:
                        echo "organos internos alcaldias";
                        break;
                    case 7:
                        redirect('admin/C_administradores');
                        break;
                    case 8:
                        echo "administración y finanzas";
                        break;
                    case 9:
                        echo "vigilancia movil";
                        break;
                    default:
                        echo "ningun caso";
                }
            }

            // redirect('admin/c_administradores');
		} else{
            $this->load->view('v_login');
		}
    }
    
    public function logeo()
    {
        $usuario = $this->input->POST('usuario');
        $contrasenia = md5($this->input->POST('contra'));
        
        if(isset($usuario) && !empty($usuario) && isset($contrasenia) && !empty($contrasenia)){
            $consulta = $this->m_login->consulta($usuario,$contrasenia);
            
            if(isset($consulta) && !empty($consulta)){
                $verificaSesiones = $this->consultaSesiones($usuario);

                if(isset($verificaSesiones) && $verificaSesiones !=null){
                    $verificaSesiones = implode(",",$verificaSesiones);

                    $this->m_login->destruye_sesiones($verificaSesiones);
                    // echo 'se encontro una sesion activa, ha sido borrada';
                }else{
                    $array_dataUser = array(
                        'logeado' => TRUE,
                        'id_usuario' => $consulta[0]['id_usuario'],
                        'nom_usuario' => $consulta[0]['nom_usuario'],
                        'nombre' => $consulta[0]['nombre'],
                        'apellido_p' => $consulta[0]['apellido_p'],
                        'apellido_m' => $consulta[0]['apellido_m'],
                        'id_perfil' => $consulta[0]['id_perfil'],
                        'activo' => $consulta[0]['activo'],
                        'id_direccion' => $consulta[0]['id_direccion'],
                        'sesion_activa' => $consulta[0]['sesion_activa']
                    );
                    $this->session->set_userdata($array_dataUser);   
                }
                //var_dump($_SESSION);die();
                if($_SESSION['sesion_activa'] == 'f'){
                    // registrar la sesion
                    $this->m_login->registra_sesion($array_dataUser);
                    // haz cambio de bandera a sesion activa = t
                    $this->m_login->set_session();
                    // echo '<h1>ya estamos dentro</h1>';
                }else{
                    // echo 'sesion activa';
                }
            }else{
                echo 'no encontre ese usuario';
            }
        }
        $this->index();
    }

    public function consultaSesiones($usuario)
    {
        $sesiones_abiertas = $this->m_login->consultaSesiones();

        $data=array();
        $ids = array();
		// if(isset($sesiones_abiertas) && !empty($sesiones_abiertas)){
        if(isset($sesiones_abiertas) && $sesiones_abiertas !=null){    
			foreach ($sesiones_abiertas as $data) {
				if(strpos(base64_decode($data['data']),$usuario) != false || strpos(base64_decode($data['data']),$usuario) > 0) {
					$ids[] = "'".$data['id']."'";
				} 
			}
        }
        if(!empty($ids)){
            return $ids;
        }else{
            return null;
        }
		
    }

    public function cerrar_sesion()
    {
        $this->m_login->set_cierre();
        $this->m_login->cierraSesion();
        session_destroy();
        // $this->index();
        $this->load->view('v_login');
    }
}

?>
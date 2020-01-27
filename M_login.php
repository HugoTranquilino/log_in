<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_login extends CI_Model{
    public function __construct() {
        parent::__construct();
    }

    // consulta de usuarios, para el login
    public function consulta($user,$contra)
    {
        $selecciona = "SELECT * FROM usuarios WHERE nom_usuario = '".$user."' AND contrasenia = '".$contra."'";
        $query = $this->db->query($selecciona)->result_array();
		return $query;
    }

    // registra la sesion en base de datos
    public function registra_sesion()
    {
        $datos_usuario = array(
            'nom_usuario' => $this->session->userdata('nom_usuario'),
            'id_perfil' => $this->session->userdata('id_perfil')
        );
        // $insert_id = $this->db->insert_id();
        // $IdSesion = $this->db->insert_id();
        $this->db->insert('sesiones',$datos_usuario);
        // $this->db->insert(userdata($IdSesion));
    }

    // cambia la bandera de inicio de sesion
    public function set_session()
    {
        $usuario = $this->session->userdata('id_usuario');
        
        $this->db->set('sesion_activa','t');
        $this->db->where('id_usuario',$usuario);
        $this->db->update('usuarios');
    
        $this->session->set_userdata('sesion_activa','t');
    }
// --------------------------------------------------------------------------------------
    // Finaliza la sesion activa
	public function cierraSesion()
	{
        $fecha =date('Y-m-d H:m:i');
		$usuario = $this->session->userdata('nom_usuario');

		$this->db->set('final_sesion',$fecha);
		$this->db->where('nom_usuario',$usuario);
		$this->db->update('sesiones'); 
    }

    public function set_cierre()
    {
        $registrar_cierreSesion = $this->session->userdata('id_usuario');

		$this->db->set('sesion_activa','f');
		$this->db->where('id_usuario',$registrar_cierreSesion);
		$this->db->update('usuarios');

		$this->session->set_userdata('sesion_activa', 'f'); 
    }

    public function destruye_sesiones($ids)
    {
        # code...
        $sql="DELETE FROM ci_sessions WHERE id in(".$ids.")";
		$this->db->query($sql);
    }
    
    //cnsulta las sesiones iniciadas
	public function consultaSesiones()
	{
		$this->db->select('id, data');
		$query = $this->db->get('ci_sessions');
		if($query->num_rows() > 0){
			return $query->result_array();
		}else {
			return null;
		}
	}
}
?>
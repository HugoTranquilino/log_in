<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_login extends CI_Model
{

  function __construct()
  {
    parent::__construct();
  }

  function consulta_usuarios($usuario,$password)
  {
    $sql="SELECT * FROM usuarios WHERE nom_usuario='".$usuario."' AND password ='".$password."'";
    $query = $this->db->query($sql)->result_array();
		return $query;
  }

  function set_session()
  {

  }

  function registra_sesion()
  {

  }
}
?>

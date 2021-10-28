<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
header("Content-type: application/json;chartset=UTF-8");
header("Access-Control-Allow-Headers: *");

require_once dirname(__DIR__) . '/func/operaciones.php';

$headers = getallheaders();
$respuesta = array();
if (isset($headers['token'])) {
    $token = $headers['token'];
    require_once dirname(__DIR__) . '/func/constantes.php';
    if ($token == API_KEY) {
        $operations = new Operaciones();
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if (isset($_GET['accion'])) {
                $accion = $_GET['accion'];
                switch ($accion) {
                    case 'users':
                        if (isset($_GET['index']) && isset($_GET['limit'])) {
                            $data = $operations->getUsers($_GET['index'], $_GET['limit']);
                            if (count($data) > 0) {
                                $respuesta['error'] = false;
                                $respuesta['mensaje'] = 'Datos encontrados';
                                $respuesta['users'] = $data;
                            } else {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'Faltan parametros';

                            }
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';

                        }
                        break;
                    case 'roles':
                        $data = $operations->getRoles();
                        if (count($data) > 0) {
                            $respuesta['error'] = false;
                            $respuesta['mensaje'] = 'Datos encontrados';
                            $respuesta['roles'] = $data;
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    case 'typeInci':
                        $data = $operations->getTypeIncid();
                        if (count($data) > 0) {
                            $respuesta['error'] = false;
                            $respuesta['mensaje'] = 'Datos encontrados';
                            $respuesta['typeInci'] = $data;
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    case 'incidences':
                        if (isset($_GET['idTypeIncid']) && isset($_GET['index']) && isset($_GET['limit'])) {
                            $count = $operations->getIncidTotal($_GET['idTypeIncid'], $_GET['index'], $_GET['limit']);
                            $data = $operations->getIncid($_GET['idTypeIncid'], $_GET['index'], $_GET['limit']);
                            if (count($data) > 0) {
                                $respuesta['error'] = false;
                                $respuesta['mensaje'] = 'Datos encontrados';
                                if (count($count) > 0) {
                                    $respuesta['total'] = $count[0]['count'];
                                } else {
                                    $respuesta['total'] = 0;
                                }
                                $respuesta['incidences'] = $data;
                            } else {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'Faltan parametros';
                            }
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    case 'incidencesUser':
                        if (isset($_GET['idUser']) && isset($_GET['index']) && isset($_GET['limit'])) {
                            $data = $operations->getIncidUser($_GET['idUser'], $_GET['index'], $_GET['limit']);
                            if (count($data) > 0) {
                                $respuesta['error'] = false;
                                $respuesta['mensaje'] = 'Datos encontrados';
                                $respuesta['incidences'] = $data;
                            } else {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'Faltan parametros';
                            }
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    case 'soluInci':
                        if (isset($_GET['index']) && isset($_GET['limit'])) {
                            $data = $operations->getSolutInci($_GET['index'], $_GET['limit']);
                            if (count($data) > 0) {
                                $respuesta['error'] = false;
                                $respuesta['mensaje'] = 'Datos encontrados';
                                $respuesta['soluInci'] = $data;
                            } else {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'Faltan parametros';
                            }
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    default:
                        $respuesta['error'] = true;
                        $respuesta['mensaje'] = 'Falta el parametro de acción';
                        break;
                }
            } else {
                $respuesta['error'] = true;
                $respuesta['mensaje'] = 'Falta el parametro de acción';
            }
        } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['accion'])) {
                $accion = $_POST['accion'];
                switch ($accion) {
                    case 'login':
                        if (isset($_POST['user']) && isset($_POST['password'])) {
                            $data = $operations->login($_POST['user'], $_POST['password']);
                            if (count($data) > 0) {
                                $respuesta['error'] = false;
                                $respuesta['mensaje'] = 'Login correcto';
                                $respuesta['login'] = $data;
                            } else {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'Usuario y/o contraseña incorrecta';
                            }
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    case 'incidence':
                        if (isset($_POST['idTypeIncid']) && isset($_POST['idUser']) && isset($_POST['title']) &&
                            isset($_POST['description']) && isset($_POST['pdf'])) {
                            if ($operations->creaIncid($_POST['idTypeIncid'], $_POST['idUser'], $_POST['title'],
                                $_POST['description'], $_POST['pdf'])) {
                                $respuesta['error'] = false;
                                $respuesta['mensaje'] = 'Incidencia guardada';
                            } else {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'No se guardo la incidencia';
                            }
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    case 'solution':
                        if (isset($_POST['idIncid']) && isset($_POST['idUser']) && isset($_POST['title']) &&
                            isset($_POST['description']) && isset($_POST['pdf'])) {
                            if ($operations->creaSolutInci($_POST['idIncid'], $_POST['idUser'], $_POST['title'],
                                $_POST['description'], $_POST['pdf'])) {
                                $respuesta['error'] = false;
                                $respuesta['mensaje'] = 'Solución guardada';
                            } else {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'No se guardo la solución';
                            }
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;

                    default:
                        $respuesta['error'] = true;
                        $respuesta['mensaje'] = 'Falta el parametro de acción';
                        break;
                }
            } else {
                $respuesta['error'] = true;
                $respuesta['mensaje'] = 'Falta el parametro de acción';
            }
        } else if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            parse_str(file_get_contents('php://input'), $put_vars);
            if (isset($put_vars['accion'])) {
                $accion = $put_vars['accion'];
                switch ($accion) {
                    case 'updaPassw':
                        if (isset($put_vars['idUser']) && isset($put_vars['user']) &&
                            isset($put_vars['oldPassword']) && isset($put_vars['newPassword'])) {
                            if ($operations->updaPassw($put_vars['idUser'], $put_vars['user'], $put_vars['oldPassword'], $put_vars['newPassword'])) {
                                $respuesta['error'] = false;
                                $respuesta['mensaje'] = 'Contraseña actualizada exitosamente';
                            } else {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'La contraseña no pudo ser actualizada';
                            }
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    case 'updaRole':
                        if (isset($put_vars['idUser']) && isset($put_vars['user']) &&
                            isset($put_vars['idRole'])) {
                            if ($operations->updaRole($put_vars['idUser'], $put_vars['user'], $put_vars['idRole'])) {
                                $respuesta['error'] = false;
                                $respuesta['mensaje'] = 'Rol actualizado exitosamente';
                            } else {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'El rol no pudo ser actualizado';
                            }
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    case 'updaIncid':
                        if (isset($put_vars['idIncid']) && isset($put_vars['idTypeIncid']) && isset($put_vars['title']) &&
                            isset($put_vars['description']) && isset($put_vars['pdf'])) {
                            if ($operations->updaIncid($put_vars['idIncid'], $put_vars['idTypeIncid'], $put_vars['title'],
                                $put_vars['description'], $put_vars['pdf'])) {
                                $respuesta['error'] = false;
                                $respuesta['mensaje'] = 'Incidencia actualizada exitosamente';
                            } else {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'La incidencia no pudo ser actualizada';
                            }
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    case 'updaSolutInci':
                        if (isset($put_vars['idSolutInci']) && isset($put_vars['title']) &&
                            isset($put_vars['description']) && isset($put_vars['pdf'])) {
                            if ($operations->updaSolutInci($put_vars['idSolutInci'], $put_vars['title'],
                                $put_vars['description'], $put_vars['pdf'])) {
                                $respuesta['error'] = false;
                                $respuesta['mensaje'] = 'Solución Incidencia actualizada exitosamente';
                            } else {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'La solución incidencia no pudo ser actualizada';
                            }
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    default:
                        $respuesta['error'] = true;
                        $respuesta['mensaje'] = 'Falta parametro';
                        break;
                }
            } else {
                $respuesta['error'] = true;
                $respuesta['mensaje'] = 'Falta el parametro de acción';
            }
        } else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            parse_str(file_get_contents('php://input'), $delete_vars);
        }
    } else {
        $respuesta['error'] = true;
        $respuesta['mensaje'] = 'Token incorrecto';
    }
} else {
    $respuesta['error'] = true;
    $respuesta['mensaje'] = 'Falta el token';
}
echo json_encode($respuesta, JSON_NUMERIC_CHECK);
<?php

require_once dirname(__FILE__) . '/conexion.php';

class Operaciones
{
    private $con;
    private $ubicacionInci;
    private $ubicacionSolu;
    private $linkInci;
    private $linkSolu;

    public function __construct()
    {
        $db = new Conexion();
        $this->con = $db->connect();
        $this->ubicacionInci = dirname(__DIR__) . '/pdfs/incidences/';
        $this->ubicacionSolu = dirname(__DIR__) . '/pdfs/solutions/';
        $this->linkInci = 'http://' . $_SERVER['SERVER_NAME'] . '/api_incidence/pdfs/incidences/';
        $this->linkSolu = 'http://' . $_SERVER['SERVER_NAME'] . '/api_incidence/pdfs/solutions/';
    }

    private function closeCon()
    {
        $this->con = null;
    }

    private function closeStmt(PDOStatement $stmt)
    {
        $stmt->closeCursor();
        $stmt = null;
    }

    private function printError(array $errorInfo)
    {
        //print_r($errorInfo);
    }

    public function getUsers($index, $limit)
    {
        $stmt = $this->con->prepare('call getUsers(?,?)');
        $stmt->bindParam(1, $index, PDO::PARAM_INT);
        $stmt->bindParam(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->closeStmt($stmt);
        return $data;
    }

    public function login($user, $password)
    {
        $stmt = $this->con->prepare('call login(?,?)');
        $stmt->bindParam(1, $user);
        $stmt->bindParam(2, $password);
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->closeStmt($stmt);
        return $data;
    }

    public function updaPassw($id, $user, $oldPassword, $newPassword)
    {
        $stmt = $this->con->prepare('call updaPassw(?,?,?,?)');
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->bindParam(2, $user);
        $stmt->bindParam(3, $oldPassword);
        $stmt->bindParam(4, $newPassword);
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        if ($stmt->rowCount() > 0) {
            $this->closeStmt($stmt);
            $this->closeCon();
            return true;
        }
        return false;
    }

    public function updaRole($id, $user, $idRole)
    {
        $stmt = $this->con->prepare('call updaRole(?,?,?)');
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->bindParam(2, $user);
        $stmt->bindParam(3, $idRole, PDO::PARAM_INT);
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        if ($stmt->rowCount() > 0) {
            $this->closeStmt($stmt);
            $this->closeCon();
            return true;
        }
        return false;
    }

    public function getRoles()
    {
        $stmt = $this->con->prepare('call getRoles()');
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->closeStmt($stmt);
        return $data;
    }

    public function getTypeIncid()
    {
        $stmt = $this->con->prepare('call getTypeIncid()');
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->closeStmt($stmt);
        return $data;
    }

    public function getIncidTotal($idTypeIncid, $index, $limit)
    {
        $idTypeIncid = '%' . $idTypeIncid . '%';
        $stmt = $this->con->prepare('call getIncidTotal(?,?,?)');
        $stmt->bindParam(1, $idTypeIncid);
        $stmt->bindParam(2, $index, PDO::PARAM_INT);
        $stmt->bindParam(3, $limit, PDO::PARAM_INT);
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->closeStmt($stmt);
        return $data;
    }

    public function getIncid($idTypeIncid, $index, $limit)
    {
        $idTypeIncid = '%' . $idTypeIncid . '%';
        $stmt = $this->con->prepare('call getIncid(?,?,?)');
        $stmt->bindParam(1, $idTypeIncid);
        $stmt->bindParam(2, $index, PDO::PARAM_INT);
        $stmt->bindParam(3, $limit, PDO::PARAM_INT);
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->closeStmt($stmt);
        return $data;
    }

    public function getIncidUser($idUser, $index, $limit)
    {
        $stmt = $this->con->prepare('call getIncidUser(?,?,?)');
        $stmt->bindParam(1, $idUser, PDO::PARAM_INT);
        $stmt->bindParam(2, $index, PDO::PARAM_INT);
        $stmt->bindParam(3, $limit, PDO::PARAM_INT);
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->closeStmt($stmt);
        return $data;
    }

    public function creaIncid($idTypeIncid, $idUser, $title, $description, $pdf)
    {
        $anio = utf8_encode(strftime('%Y'));
        $mes = utf8_encode(strftime('%m'));
        $dia = utf8_encode(strftime('%d'));
        $hora = utf8_encode(strftime('%H:%M:%S'));
        $pdfImage = $anio . '-' . $mes . '-' . $dia . ' ' . $hora;
        $del = array(' ', '/', ':', '-');
        $pdfImage1 = str_replace($del, '', $pdfImage);
        $link = $this->linkInci . $pdfImage1 . '.pdf';
        $ubicacion = $this->ubicacionInci . $pdfImage1 . '.pdf';
        $stmt = $this->con->prepare('call creaIncid(?,?,?,?,?,?,?)');
        $stmt->bindParam(1, $idTypeIncid, PDO::PARAM_INT);
        $stmt->bindParam(2, $idUser, PDO::PARAM_INT);
        $stmt->bindParam(3, $title);
        $stmt->bindParam(4, $description);
        $stmt->bindParam(5, $link);
        $stmt->bindParam(6, $pdfImage);
        $stmt->bindParam(7, $pdfImage);
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        if ($stmt->rowCount() > 0) {
            if (file_put_contents($ubicacion, base64_decode($pdf))) {
                $this->closeStmt($stmt);
                $this->closeCon();
                return true;
            } else {
                //$this->con->rollBack();
                $this->closeStmt($stmt);
                $this->closeCon();
                return false;
            }
        }
        return false;
    }

    public function updaIncid($id, $idTypeIncid, $title, $description, $pdf)
    {
        $anio = utf8_encode(strftime('%Y'));
        $mes = utf8_encode(strftime('%m'));
        $dia = utf8_encode(strftime('%d'));
        $hora = utf8_encode(strftime('%H:%M:%S'));
        $pdfImage = $anio . '-' . $mes . '-' . $dia . ' ' . $hora;
        $del = array(' ', '/', ':', '-');
        $pdfImage1 = str_replace($del, '', $pdfImage);
        $link = $this->linkInci . $pdfImage1 . '.pdf';
        $ubicacion = $this->ubicacionInci . $pdfImage1 . '.pdf';
        $stmt = $this->con->prepare('call updaIncid(?,?,?,?,?,?)');
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->bindParam(2, $idTypeIncid, PDO::PARAM_INT);
        $stmt->bindParam(3, $title);
        $stmt->bindParam(4, $description);
        $stmt->bindParam(5, $link);
        $stmt->bindParam(6, $pdfImage);
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        if ($stmt->rowCount() > 0) {
            if (file_put_contents($ubicacion, base64_decode($pdf))) {
                $this->closeStmt($stmt);
                $this->closeCon();
                return true;
            } else {
                //$this->con->rollBack();
                $this->closeStmt($stmt);
                $this->closeCon();
                return false;
            }
        }
        return false;
    }

    public function getSolutInci($index, $limit)
    {
        $stmt = $this->con->prepare('call getSolutInci(?,?)');
        $stmt->bindParam(1, $index, PDO::PARAM_INT);
        $stmt->bindParam(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->closeStmt($stmt);
        return $data;
    }

    public function creaSolutInci($idIncid, $idUser, $title, $description, $pdf)
    {
        $anio = utf8_encode(strftime('%Y'));
        $mes = utf8_encode(strftime('%m'));
        $dia = utf8_encode(strftime('%d'));
        $hora = utf8_encode(strftime('%H:%M:%S'));
        $pdfImage = $anio . '-' . $mes . '-' . $dia . ' ' . $hora;
        $del = array(' ', '/', ':', '-');
        $pdfImage1 = str_replace($del, '', $pdfImage);
        $link = $this->linkSolu . $pdfImage1 . '.pdf';
        $ubicacion = $this->ubicacionSolu . $pdfImage1 . '.pdf';
        $stmt = $this->con->prepare('call creaSolutInci(?,?,?,?,?,?,?)');
        $stmt->bindParam(1, $idIncid, PDO::PARAM_INT);
        $stmt->bindParam(2, $idUser, PDO::PARAM_INT);
        $stmt->bindParam(3, $title);
        $stmt->bindParam(4, $description);
        $stmt->bindParam(5, $link);
        $stmt->bindParam(6, $pdfImage);
        $stmt->bindParam(7, $pdfImage);
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        if ($stmt->rowCount() > 0) {
            if (file_put_contents($ubicacion, base64_decode($pdf))) {
                $this->closeStmt($stmt);
                $this->closeCon();
                return true;
            } else {
                //$this->con->rollBack();
                $this->closeStmt($stmt);
                $this->closeCon();
                return false;
            }
        }
        return false;
    }

    public function updaSolutInci($id, $title, $description, $pdf)
    {
        $anio = utf8_encode(strftime('%Y'));
        $mes = utf8_encode(strftime('%m'));
        $dia = utf8_encode(strftime('%d'));
        $hora = utf8_encode(strftime('%H:%M:%S'));
        $pdfImage = $anio . '-' . $mes . '-' . $dia . ' ' . $hora;
        $del = array(' ', '/', ':', '-');
        $pdfImage1 = str_replace($del, '', $pdfImage);
        $link = $this->linkSolu . $pdfImage1 . '.pdf';
        $ubicacion = $this->ubicacionSolu . $pdfImage1 . '.pdf';
        $stmt = $this->con->prepare('call updaSolutInci(?,?,?,?,?)');
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->bindParam(2, $title);
        $stmt->bindParam(3, $description);
        $stmt->bindParam(4, $link);
        $stmt->bindParam(5, $pdfImage);
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        if ($stmt->rowCount() > 0) {
            if (file_put_contents($ubicacion, base64_decode($pdf))) {
                $this->closeStmt($stmt);
                $this->closeCon();
                return true;
            } else {
                //$this->con->rollBack();
                $this->closeStmt($stmt);
                $this->closeCon();
                return false;
            }
        }
        return false;
    }
}
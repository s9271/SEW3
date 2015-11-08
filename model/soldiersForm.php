<?php
class SoldiersForm {

  private $SoldiersGateway    = NULL;
  private function openDb() {
    if (!mysql_connect("localhost", "root", "")) {
      throw new Exception("Nie udało się połączyć z bazą");
    }
    if (!mysql_select_db("login")) {
      throw new Exception("Nie znaleziono na serwerze bazy login.");
    }
  }

  private function closeDb() {
    mysql_close();
  }

  public function __construct() {
    $this->soldiersGateway = new SoldiersGateway();
  }

  public function getAllSoldiers($order) {
    try {
      $this->openDb();
      $res = $this->soldiersGateway->selectAll($order);
      $this->closeDb();
      return $res;
    } catch (Exception $e) {
      $this->closeDb();
      throw $e;
      }
    }

  public function getSoldier($id) {
    try {
      $this->openDb();
      $res = $this->soldiersGateway->selectById($id);
      $this->closeDb();
      return $res;
      } catch (Exception $e) {
          $this->closeDb();
          throw $e;
      }
      return $this->soldiersGateway->find($id);
    }


    public function createNewSoldier( $soldierName, $soldierSurname, $birthday, $sex, $phone, $email, $code, $city, $street, $numberHouse, $militaryRank, $jwNumber, $missions, $training, $weapon, $weaponsNumber, $equipmentSoldier, $someText ) {
      try {
        $this->openDb();
        $res = $this->soldiersGateway->insert( $soldierName, $soldierSurname, $birthday, $sex, $phone, $email, $code, $city, $street, $numberHouse, $militaryRank, $jwNumber, $missions, $training, $weapon, $weaponsNumber, $equipmentSoldier, $someText );
        $this->closeDb();
        return $res;
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
    }

  public function deleteSoldier( $id ) {
    try {
      $this->openDb();
      $res = $this->soldiersGateway->delete($id);
      $this->closeDb();
      } catch (Exception $e) {
      $this->closeDb();
      throw $e;
      }
    }
}
?>

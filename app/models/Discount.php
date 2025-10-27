<?php
class Discount {
    private $id;
    private $code;
    private $moneyDeduct;
    private $condition;
    private $quantity;
    private $status;

    public function __construct($id = null, $code = '', $moneyDeduct = 0, $condition = '', $quantity = 0, $status = 'Activate') {
        $this->id = $id;
        $this->code = $code;
        $this->moneyDeduct = $moneyDeduct;
        $this->condition = $condition;
        $this->quantity = $quantity;
        $this->status = $status;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getCode() {
        return $this->code;
    }

    public function getMoneyDeduct() {
        return $this->moneyDeduct;
    }

    public function getCondition() {
        return $this->condition;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function getStatus() {
        return $this->status;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setCode($code) {
        $this->code = $code;
    }

    public function setMoneyDeduct($moneyDeduct) {
        $this->moneyDeduct = $moneyDeduct;
    }

    public function setCondition($condition) {
        $this->condition = $condition;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    public function setStatus($status) {
        $this->status = $status;
    }
}
?>

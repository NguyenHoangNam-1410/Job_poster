<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/Discount.php';

class DiscountDAO {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->conn;
    }
    
    /**
     * Create a new discount
     */
    public function create($discount) {
        try {
            $sql = "INSERT INTO DISCOUNT_COUPON (Code, MoneyDeduct, Condition, Quantity, Status) 
                    VALUES (?, ?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($sql);
            
            $code = $discount->getCode();
            $moneyDeduct = $discount->getMoneyDeduct();
            $condition = $discount->getCondition();
            $quantity = $discount->getQuantity();
            $status = $discount->getStatus();
            
            $stmt->bind_param("sdsis", $code, $moneyDeduct, $condition, $quantity, $status);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("DiscountDAO create error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all discounts
     */
    public function getAll() {
        try {
            $sql = "SELECT * FROM DISCOUNT_COUPON ORDER BY Discount_ID DESC";
            $result = $this->conn->query($sql);
            
            $discounts = [];
            while ($row = $result->fetch_assoc()) {
                $discounts[] = new Discount(
                    $row['Discount_ID'],
                    $row['Code'],
                    floatval($row['MoneyDeduct']), // Convert to float
                    $row['Condition'],
                    intval($row['Quantity']), // Convert to int
                    $row['Status']
                );
            }
            
            return $discounts;
        } catch (Exception $e) {
            error_log("DiscountDAO getAll error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get discount by ID
     */
    public function getById($id) {
        try {
            $sql = "SELECT * FROM DISCOUNT_COUPON WHERE Discount_ID = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                return new Discount(
                    $row['Discount_ID'],
                    $row['Code'],
                    floatval($row['MoneyDeduct']), // Convert to float
                    $row['Condition'],
                    intval($row['Quantity']), // Convert to int
                    $row['Status']
                );
            }
            
            return null;
        } catch (Exception $e) {
            error_log("DiscountDAO getById error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Update a discount
     */
    public function update($discount) {
        try {
            $sql = "UPDATE DISCOUNT_COUPON 
                    SET Code = ?, MoneyDeduct = ?, Condition = ?, Quantity = ?, Status = ?
                    WHERE Discount_ID = ?";
            
            $stmt = $this->conn->prepare($sql);
            
            $code = $discount->getCode();
            $moneyDeduct = $discount->getMoneyDeduct();
            $condition = $discount->getCondition();
            $quantity = $discount->getQuantity();
            $status = $discount->getStatus();
            $id = $discount->getId();
            
            $stmt->bind_param("sdsisi", $code, $moneyDeduct, $condition, $quantity, $status, $id);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("DiscountDAO update error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get discount by code
     */
    public function getByCode($code) {
        try {
            $sql = "SELECT * FROM DISCOUNT_COUPON WHERE Code = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $code);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                return new Discount(
                    $row['Discount_ID'],
                    $row['Code'],
                    floatval($row['MoneyDeduct']), // Convert to float
                    $row['Condition'],
                    intval($row['Quantity']), // Convert to int
                    $row['Status']
                );
            }
            
            return null;
        } catch (Exception $e) {
            error_log("DiscountDAO getByCode error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Delete a discount
     */
    public function delete($id) {
        try {
            $sql = "DELETE FROM DISCOUNT_COUPON WHERE Discount_ID = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
        } catch (Exception $e) {
            error_log("DiscountDAO delete error: " . $e->getMessage());
        }
    }
}
?>

<?php
require_once __DIR__ . '/../dao/DiscountDAO.php';
require_once __DIR__ . '/../models/Discount.php';

class DiscountService {
    private $discountDAO;

    public function __construct() {
        $this->discountDAO = new DiscountDAO();
    }

    public function getAllDiscounts() {
        return $this->discountDAO->getAll();
    }

    public function getDiscountById($id) {
        return $this->discountDAO->getById($id);
    }

    public function createDiscount($data) {
        if (empty($data['code']) || empty($data['money_deduct'])) {
            throw new Exception("Missing required fields");
        }

        $discount = new Discount(
            null,
            trim($data['code']),
            floatval($data['money_deduct']),
            $data['dieu_kien'] ?? '',
            intval($data['so_luong'] ?? 0),
            $data['trang_thai'] ?? 'Inactive'
        );

        return $this->discountDAO->create($discount);
    }

    public function updateDiscount($id, $data) {
        $discount = new Discount(
            $id,
            trim($data['code']),
            floatval($data['money_deduct']),
            $data['dieu_kien'] ?? '',
            intval($data['so_luong'] ?? 0),
            $data['trang_thai'] ?? 'Inactive'
        );

        return $this->discountDAO->update($discount);
    }

    public function deleteDiscount($id) {
        return $this->discountDAO->delete($id);
    }
}
?>

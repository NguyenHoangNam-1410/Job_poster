<?php
require_once __DIR__ . '/../services/DiscountService.php';

class DiscountController {
    private $discountService;

    public function __construct() {
        $this->discountService = new DiscountService();
    }

    public function index() {
        $discounts = $this->discountService->getAllDiscounts();
        require_once __DIR__ . '/../views/admin/discounts/list.php';
    }

    public function create() {
        $mode = 'create';
        $discount = null;
        require_once __DIR__ . '/../views/admin/discounts/form.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->discountService->createDiscount($_POST);
                header('Location: /Job_poster/public/discounts');
                exit;
            } catch (Exception $e) {
                echo "Error creating discount: " . $e->getMessage();
            }
        }
    }

    public function edit($id) {
        $mode = 'edit';
        $discount = $this->discountService->getDiscountById($id);

        if (!$discount) {
            echo "Discount not found";
            return;
        }

        require_once __DIR__ . '/../views/admin/discounts/form.php';
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->discountService->updateDiscount($id, $_POST);
                header('Location: /Job_poster/public/discounts');
                exit;
            } catch (Exception $e) {
                echo "Error updating discount: " . $e->getMessage();
            }
        }
    }

    public function destroy($id) {
        header('Content-Type: application/json');

        try {
            $this->discountService->deleteDiscount($id);
            echo json_encode(['success' => true, 'message' => 'Discount deleted']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
}
?>

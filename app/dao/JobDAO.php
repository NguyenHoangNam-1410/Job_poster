<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/Job.php';

class JobDAO
{
    private $db;
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->conn;
        $this->conn = $database->conn;
    }

    public function create(Job $job)
    {
        $sql = "INSERT INTO JOBS (employer_id, posted_by, title, location, description, requirements, salary, deadline, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        $employerId = $job->getEmployerId();
        $postedBy = $job->getPostedBy();
        $title = $job->getTitle();
        $location = $job->getLocation();
        $description = $job->getDescription();
        $requirements = $job->getRequirements();
        $salary = $job->getSalary();
        $deadline = $job->getDeadline();
        $status = $job->getStatus();

        $stmt->bind_param(
            "iissssdss",
            $employerId,
            $postedBy,
            $title,
            $location,
            $description,
            $requirements,
            $salary,
            $deadline,
            $status
        );

        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function getAll($search = '', $categoryFilter = '', $locationFilter = '', $statusFilter = '', $limit = null, $offset = 0)
    {
        $sql = "SELECT DISTINCT j.id, j.employer_id, j.posted_by, j.title, j.location, j.description, 
                j.requirements, j.salary, j.deadline, j.status, j.created_at, j.updated_at, j.approved_at, j.rejected_at,
                e.company_name,
                u.Name as employer_name,
                u.Avatar as employer_avatar,
                u.Name as posted_by_name
                FROM JOBS j
                LEFT JOIN EMPLOYERS e ON j.employer_id = e.id
                LEFT JOIN USERS u ON j.posted_by = u.UID
                LEFT JOIN JOB_CATEGORY_MAP jcm ON j.id = jcm.job_id
                WHERE 1=1";

        $params = [];
        $types = '';

        if (!empty($search)) {
            $sql .= " AND j.title LIKE ?";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $types .= 's';
        }

        if (!empty($categoryFilter)) {
            $sql .= " AND jcm.category_id = ?";
            $params[] = $categoryFilter;
            $types .= 'i';
        }

        if (!empty($locationFilter)) {
            $sql .= " AND j.location LIKE ?";
            $locationTerm = "%{$locationFilter}%";
            $params[] = $locationTerm;
            $types .= 's';
        }

        if (!empty($statusFilter)) {
            if (strpos($statusFilter, ',') !== false) {
                $statuses = explode(',', $statusFilter);
                $placeholders = implode(',', array_fill(0, count($statuses), '?'));
                $sql .= " AND j.status IN ($placeholders)";
                foreach ($statuses as $status) {
                    $params[] = trim($status);
                    $types .= 's';
                }
            } else {
                $sql .= " AND j.status = ?";
                $params[] = $statusFilter;
                $types .= 's';
            }
        }

        $sql .= " ORDER BY j.created_at DESC";

        if ($limit !== null) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
            $types .= 'ii';
        }

        $stmt = $this->db->prepare($sql);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $jobs = [];

        while ($row = $result->fetch_assoc()) {
            $job = $this->mapRowToJob($row);
            $job->setCategories($this->getJobCategories($job->getId()));
            $jobs[] = $job;
        }

        return $jobs;
    }

    public function getTotalCount($search = '', $categoryFilter = '', $locationFilter = '', $statusFilter = '')
    {
        $sql = "SELECT COUNT(DISTINCT j.id) as total 
                FROM JOBS j
                LEFT JOIN JOB_CATEGORY_MAP jcm ON j.id = jcm.job_id
                WHERE 1=1";

        $params = [];
        $types = '';

        if (!empty($search)) {
            $sql .= " AND j.title LIKE ?";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $types .= 's';
        }

        if (!empty($categoryFilter)) {
            $sql .= " AND jcm.category_id = ?";
            $params[] = $categoryFilter;
            $types .= 'i';
        }

        if (!empty($locationFilter)) {
            $sql .= " AND j.location LIKE ?";
            $locationTerm = "%{$locationFilter}%";
            $params[] = $locationTerm;
            $types .= 's';
        }

        if (!empty($statusFilter)) {
            if (strpos($statusFilter, ',') !== false) {
                $statuses = explode(',', $statusFilter);
                $placeholders = implode(',', array_fill(0, count($statuses), '?'));
                $sql .= " AND j.status IN ($placeholders)";
                foreach ($statuses as $status) {
                    $params[] = trim($status);
                    $types .= 's';
                }
            } else {
                $sql .= " AND j.status = ?";
                $params[] = $statusFilter;
                $types .= 's';
            }
        }

        $stmt = $this->db->prepare($sql);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['total'];
    }

    public function getById($id)
    {
        $sql = "SELECT j.*, e.company_name, e.contact_email, e.logo as employer_logo, u.Name as employer_name, u.Avatar as employer_avatar, u.Name as posted_by_name
                FROM JOBS j
                LEFT JOIN EMPLOYERS e ON j.employer_id = e.id
                LEFT JOIN USERS u ON j.posted_by = u.UID
                WHERE j.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $job = $this->mapRowToJob($row);
            $job->setCategories($this->getJobCategories($id));
            return $job;
        }
        return null;
    }

    public function getEmployerEmailByJobId($jobId)
    {
        $sql = "SELECT e.contact_email 
                FROM JOBS j
                INNER JOIN EMPLOYERS e ON j.employer_id = e.id
                WHERE j.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $jobId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $row['contact_email'] ?? null;
        }
        return null;
    }

    public function getJobCategories($jobId)
    {
        $sql = "SELECT jc.id, jc.category_name 
                FROM JOB_CATEGORIES jc
                INNER JOIN JOB_CATEGORY_MAP jcm ON jc.id = jcm.category_id
                WHERE jcm.job_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $jobId);
        $stmt->execute();
        $result = $stmt->get_result();

        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = [
                'id' => $row['id'],
                'name' => $row['category_name']
            ];
        }
        return $categories;
    }

    public function getUniqueLocations()
    {
        $sql = "SELECT DISTINCT location FROM JOBS WHERE location IS NOT NULL AND location != '' ORDER BY location ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $locations = [];
        while ($row = $result->fetch_assoc()) {
            $locations[] = $row['location'];
        }
        return $locations;
    }

    public function update(Job $job)
    {
        $sql = "UPDATE JOBS SET 
                title = ?, 
                location = ?, 
                description = ?, 
                requirements = ?, 
                salary = ?, 
                deadline = ?,
                status = ?
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);

        $title = $job->getTitle();
        $location = $job->getLocation();
        $description = $job->getDescription();
        $requirements = $job->getRequirements();
        $salary = $job->getSalary();
        $deadline = $job->getDeadline();
        $status = $job->getStatus();
        $id = $job->getId();

        $stmt->bind_param(
            "ssssdss" . "i",
            $title,
            $location,
            $description,
            $requirements,
            $salary,
            $deadline,
            $status,
            $id
        );

        return $stmt->execute();
    }

    public function updateJobCategories($jobId, $categoryIds)
    {
        $sql = "DELETE FROM JOB_CATEGORY_MAP WHERE job_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $jobId);
        $stmt->execute();

        if (!empty($categoryIds)) {
            $sql = "INSERT INTO JOB_CATEGORY_MAP (job_id, category_id) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            foreach ($categoryIds as $categoryId) {
                $stmt->bind_param("ii", $jobId, $categoryId);
                $stmt->execute();
            }
        }

        return true;
    }

    public function changeStatus($id, $newStatus)
    {
        if ($newStatus === 'approved') {
            $approvedAt = date('Y-m-d H:i:s');
            $sql = "UPDATE JOBS SET status = ?, approved_at = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $time = $approvedAt;
            $stmt->bind_param("ssi", $newStatus, $time, $id);
        } elseif ($newStatus === 'rejected') {
            $rejectedAt = date('Y-m-d H:i:s');
            $sql = "UPDATE JOBS SET status = ?, rejected_at = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $time = $rejectedAt;
            $stmt->bind_param("ssi", $newStatus, $time, $id);
        } else {
            $sql = "UPDATE JOBS SET status = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("si", $newStatus, $id);
        }

        return $stmt->execute();
    }

    public function softDelete($id)
    {
        return $this->changeStatus($id, 'soft_deleted');
    }

    public function hardDelete($id)
    {
        try {
            $sql = "DELETE FROM JOBS WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->db->error);
            }
            $stmt->bind_param("i", $id);
            $result = $stmt->execute();
            if (!$result) {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            error_log("JobDAO hardDelete error: " . $e->getMessage());
            throw $e;
        }
    }

    private function mapRowToJob($row)
    {
        $job = new Job(
            $row['id'],
            $row['employer_id'],
            $row['posted_by'],
            $row['title'],
            $row['location'],
            $row['description'],
            $row['requirements'],
            $row['salary'],
            $row['deadline'],
            $row['status']
        );

        $job->setCreatedAt($row['created_at']);
        $job->setUpdatedAt($row['updated_at']);

        if (isset($row['company_name'])) {
            $job->setCompanyName($row['company_name']);
        }
        $job->setApprovedAt($row['approved_at'] ?? null);
        $job->setRejectedAt($row['rejected_at'] ?? null);

        if (isset($row['employer_name'])) {
            $job->setEmployerName($row['employer_name']);
        }

        if (isset($row['employer_avatar'])) {
            $job->setEmployerAvatar($row['employer_avatar']);
        }

        if (isset($row['employer_logo'])) {
            $job->setEmployerLogo($row['employer_logo']);
        }

        if (isset($row['posted_by_name'])) {
            $job->setPostedByName($row['posted_by_name']);
        }

        return $job;
    }

    public function createReview($jobId, $reviewedBy, $action, $reason = null)
    {
        $sql = "INSERT INTO JOB_REVIEWS (job_id, reviewed_by, action, reason) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiss", $jobId, $reviewedBy, $action, $reason);
        return $stmt->execute();
    }

    public function getLatestReview($jobId)
    {
        $sql = "SELECT jr.*, u.Name as reviewer_name 
                FROM JOB_REVIEWS jr
                LEFT JOIN USERS u ON jr.reviewed_by = u.UID
                WHERE jr.job_id = ? 
                ORDER BY jr.created_at DESC 
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $jobId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $row;
        }
        return null;
    }

    public function getByEmployerId($employerId)
    {
        $sql = "SELECT * FROM JOBS WHERE employer_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $employerId);
        $stmt->execute();
        $result = $stmt->get_result();

        $jobs = [];
        while ($row = $result->fetch_assoc()) {
            $jobs[] = $this->mapRowToJob($row);
        }
        return $jobs;
    }

    public function getTotalCountByEmployer(
        $employerId,
        $search,
        $categoryFilter,
        $locationFilter,
        $statusesToQuery,
        $dateFrom,
        $dateTo
    ) {
        // Convert comma-separated string to array
        if (!empty($statusesToQuery) && is_string($statusesToQuery)) {
            $statusesToQuery = explode(',', $statusesToQuery);
        }

        $sql = "SELECT COUNT(DISTINCT j.id) as total 
                FROM JOBS j
                LEFT JOIN JOB_CATEGORY_MAP jcm ON j.id = jcm.job_id
                WHERE j.employer_id = ?";

        $params = [$employerId];
        $types = 'i';

        if (!empty($search)) {
            $sql .= " AND j.title LIKE ?";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $types .= 's';
        }

        if (!empty($categoryFilter)) {
            $sql .= " AND jcm.category_id = ?";
            $params[] = $categoryFilter;
            $types .= 'i';
        }

        if (!empty($locationFilter)) {
            $sql .= " AND j.location LIKE ?";
            $locationTerm = "%{$locationFilter}%";
            $params[] = $locationTerm;
            $types .= 's';
        }

        if (!empty($statusesToQuery)) {
            $placeholders = implode(',', array_fill(0, count($statusesToQuery), '?'));
            $sql .= " AND j.status IN ($placeholders)";
            foreach ($statusesToQuery as $status) {
                $params[] = trim($status);
                $types .= 's';
            }
        }

        if (!empty($dateFrom)) {
            $sql .= " AND j.deadline >= ?";
            $params[] = $dateFrom;
            $types .= 's';
        }

        if (!empty($dateTo)) {
            $sql .= " AND j.deadline <= ?";
            $params[] = $dateTo;
            $types .= 's';
        }

        $stmt = $this->db->prepare($sql);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['total'];
    }

    public function getJobsByEmployer($employerId, $search, $categoryFilter, $locationFilter, $statusesToQuery, $dateFrom, $dateTo, $limit = null, $offset = 0)
    {
        // Convert comma-separated string to array
        if (!empty($statusesToQuery) && is_string($statusesToQuery)) {
            $statusesToQuery = explode(',', $statusesToQuery);
        }
        $sql = "SELECT j.* 
                FROM JOBS j
                WHERE j.employer_id = ?";

        $params = [$employerId];
        $types = 'i';

        if (!empty($search)) {
            $sql .= " AND j.title LIKE ?";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $types .= 's';
        }

        if (!empty($categoryFilter)) {
            $sql .= " AND EXISTS (
                SELECT 1 FROM JOB_CATEGORY_MAP jcm
                WHERE jcm.job_id = j.id AND jcm.category_id = ?
            )";
            $params[] = $categoryFilter;
            $types .= 'i';
        }

        if (!empty($locationFilter)) {
            $sql .= " AND j.location LIKE ?";
            $locationTerm = "%{$locationFilter}%";
            $params[] = $locationTerm;
            $types .= 's';
        }
        if (!empty($statusesToQuery)) {
            $placeholders = implode(',', array_fill(0, count($statusesToQuery), '?'));
            $sql .= " AND j.status IN ($placeholders)";
            foreach ($statusesToQuery as $status) {
                $params[] = trim($status);
                $types .= 's';
            }
        }
        if (!empty($dateFrom)) {
            $sql .= " AND j.deadline >= ?";
            $params[] = $dateFrom;
            $types .= 's';
        }
        if (!empty($dateTo)) {
            $sql .= " AND j.deadline <= ?";
            $params[] = $dateTo;
            $types .= 's';
        }

        if (!empty($limit)) {
            $sql .= " LIMIT ?";
            $params[] = $limit;
            $types .= 'i';
        }

        if (!empty($offset)) {
            $sql .= " OFFSET ?";
            $params[] = $offset;
            $types .= 'i';
        }

        $stmt = $this->db->prepare($sql);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $jobs = [];
        while ($row = $result->fetch_assoc()) {
            $job = $this->mapRowToJob($row);
            $job->setCategories($this->getJobCategories($job->getId()));
            $jobs[] = $job;
        }
        return $jobs;
    }

    public function getUniqueLocationsByEmployer($employerId)
    {
        $sql = "SELECT DISTINCT location 
                FROM JOBS 
                WHERE employer_id = ? 
                  AND location IS NOT NULL 
                  AND location != '' 
                ORDER BY location ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $employerId);
        $stmt->execute();
        $result = $stmt->get_result();

        $locations = [];
        while ($row = $result->fetch_assoc()) {
            $locations[] = $row['location'];
        }
        return $locations;
    }


    /* =========================================================
     * PUBLIC FILTERS + SEARCH (for /public/ajax/* endpoints)
     * =======================================================*/
    public function searchPublic($q, $categoryIds, $locationIds, $statusIds, $page, $perPage)
    {
        $page = max(1, (int) $page);
        $perPage = max(1, (int) $perPage);
        $off = ($page - 1) * $perPage;

        // Backward compatibility: if single int passed, convert to array
        if (is_int($categoryIds)) {
            $categoryIds = $categoryIds > 0 ? [$categoryIds] : [];
        }
        if (!is_array($categoryIds)) {
            $categoryIds = [];
        }
        $categoryIds = array_filter(array_map('intval', $categoryIds), fn($id) => $id > 0);

        // Handle locationIds: backward compatibility
        if (is_string($locationIds)) {
            $locationIds = $locationIds !== '' ? [$locationIds] : [];
        }
        if (!is_array($locationIds)) {
            $locationIds = [];
        }
        $locationIds = array_filter(array_map('trim', $locationIds), fn($l) => $l !== '');

        // Handle statusIds: backward compatibility
        if (is_string($statusIds)) {
            $statusIds = $statusIds !== '' ? [$statusIds] : [];
        }
        if (!is_array($statusIds)) {
            $statusIds = [];
        }
        $statusIds = array_filter(array_map('trim', $statusIds), fn($s) => $s !== '');

        $where = [];
        $binds = [];
        $types = '';

        // Handle multiple statuses: if any status selected, filter by them; otherwise show all public
        if (!empty($statusIds)) {
            $statusConditions = [];
            foreach ($statusIds as $st) {
                if ($st === 'recruiting') {
                    $statusConditions[] = "((JOBS.status='approved' AND JOBS.deadline >= NOW()) OR JOBS.status='recruiting')";
                } elseif ($st === 'overdue') {
                    $statusConditions[] = "((JOBS.status='approved' AND JOBS.deadline < NOW()) OR JOBS.status='overdue')";
                } elseif ($st === 'all') {
                    $statusConditions[] = "(JOBS.status IN ('approved','recruiting','overdue'))";
                } else {
                    $statusConditions[] = "JOBS.status = ?";
                    $binds[] = $st;
                    $types .= 's';
                }
            }
            if (!empty($statusConditions)) {
                $where[] = "(" . implode(" OR ", $statusConditions) . ")";
            }
        } else {
            // Default: show all public jobs
            $where[] = "(JOBS.status IN ('approved','recruiting','overdue'))";
        }


        if ($q !== '') {
            $where[] =
                "("
                . "JOBS.title LIKE CONCAT('%', ?, '%')"
                . " OR JOBS.description LIKE CONCAT('%', ?, '%')"
                . " OR e.company_name LIKE CONCAT('%', ?, '%')"
                . " OR EXISTS (SELECT 1 FROM JOB_CATEGORY_MAP m2 "
                . "            JOIN JOB_CATEGORIES c2 ON c2.id = m2.category_id "
                . "            WHERE m2.job_id = JOBS.id AND c2.category_name LIKE CONCAT('%', ?, '%'))"
                . ")";
            $binds[] = $q;
            $binds[] = $q;
            $binds[] = $q;
            $binds[] = $q;
            $types .= 'ssss';
        }

        // Handle multiple categories: find jobs that have ALL selected categories
        if (!empty($categoryIds)) {
            $catCount = count($categoryIds);
            $inPlaceholders = implode(',', array_fill(0, $catCount, '?'));
            // Use subquery to find jobs that have ALL the selected categories
            $categorySubquery = "(
            SELECT job_id 
            FROM JOB_CATEGORY_MAP 
            WHERE category_id IN ($inPlaceholders)
            GROUP BY job_id 
            HAVING COUNT(DISTINCT category_id) = $catCount
        )";
            $where[] = "JOBS.id IN $categorySubquery";
            foreach ($categoryIds as $catId) {
                $binds[] = $catId;
                $types .= 'i';
            }
        }

        // Handle multiple locations: job must match ANY of the selected locations
        if (!empty($locationIds)) {
            $locConditions = [];
            foreach ($locationIds as $loc) {
                $locConditions[] = "JOBS.location LIKE CONCAT('%', ?, '%')";
                $binds[] = $loc;
                $types .= 's';
            }
            if (!empty($locConditions)) {
                $where[] = "(" . implode(" OR ", $locConditions) . ")";
            }
        }

        $whereSql = $where ? ("WHERE " . implode(" AND ", $where)) : "";

        // Count query
        $sqlCnt = "SELECT COUNT(*) AS c 
               FROM JOBS 
               LEFT JOIN EMPLOYERS e ON e.id = JOBS.employer_id 
               $whereSql";
        $stmtCnt = $this->conn->prepare($sqlCnt);
        if ($types !== '')
            $stmtCnt->bind_param($types, ...$binds);
        $stmtCnt->execute();
        $total = (int) ($stmtCnt->get_result()->fetch_assoc()['c'] ?? 0);

        // Main query
        $sql = "
      SELECT
        JOBS.id,
        JOBS.title,
        COALESCE(e.company_name,'') AS company,
        JOBS.location,
        JOBS.salary,
        JOBS.created_at AS posted_at,
        JOBS.deadline,
        CASE
          WHEN JOBS.status='approved' AND JOBS.deadline < NOW() THEN 'overdue'
          WHEN JOBS.status='approved' THEN 'recruiting'
          ELSE JOBS.status
        END AS public_status,
        COALESCE(e.logo, '') AS thumbnail_url
      FROM JOBS
      LEFT JOIN EMPLOYERS e ON e.id = JOBS.employer_id
      $whereSql
      ORDER BY JOBS.created_at DESC, JOBS.id DESC
      LIMIT ? OFFSET ?
    ";

        $stmt = $this->conn->prepare($sql);
        if ($types === '') {
            $stmt->bind_param("ii", $perPage, $off);
        } else {
            $mergedParams = array_merge($binds, [$perPage, $off]);
            $stmt->bind_param($types . "ii", ...$mergedParams);
        }
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $ids = array_map(fn($r) => (int) $r['id'], $rows);
        $tagsMap = [];
        if (!empty($ids)) {
            $in = implode(",", array_fill(0, count($ids), "?"));
            $inType = str_repeat("i", count($ids));
            $sqlTag = "
          SELECT m.job_id, c.category_name AS name
          FROM JOB_CATEGORY_MAP m
          JOIN JOB_CATEGORIES c ON c.id = m.category_id
          WHERE m.job_id IN ($in)
          ORDER BY c.category_name
        ";
            $stmtT = $this->conn->prepare($sqlTag);
            $stmtT->bind_param($inType, ...$ids);
            $stmtT->execute();
            $rsT = $stmtT->get_result();
            while ($r = $rsT->fetch_assoc())
                $tagsMap[(int) $r['job_id']][] = $r['name'];
        }
        foreach ($rows as &$r)
            $r['tags'] = $tagsMap[(int) $r['id']] ?? [];

        return ['total' => $total, 'rows' => $rows];
    }

}

/* ===== Additional helper added at bottom (non-breaking) ===== */
if (!function_exists('jp_get_related_public_jobs')) {
    /**
     * Fetch related public jobs by same company, location, or overlapping categories.
     * Returns an array of rows similar to searchPublic() items.
     */
    function jp_get_related_public_jobs(int $jobId, int $limit = 12): array
    {
        $database = new Database();
        $conn = $database->conn;

        // Get base info
        $stmtBase = $conn->prepare("SELECT employer_id, location FROM JOBS WHERE id = ?");
        if (!$stmtBase)
            return [];
        $stmtBase->bind_param("i", $jobId);
        $stmtBase->execute();
        $base = $stmtBase->get_result()->fetch_assoc();
        if (!$base)
            return [];
        $employerId = (int) ($base['employer_id'] ?? 0);
        $location = (string) ($base['location'] ?? '');

        $sql = "
          SELECT
            j.id,
            j.title,
            COALESCE(e.company_name,'') AS company,
            j.location,
            j.created_at AS posted_at,
            j.deadline,
            CASE
              WHEN j.status='approved' AND j.deadline < NOW() THEN 'overdue'
              WHEN j.status='approved' THEN 'recruiting'
              ELSE j.status
            END AS public_status,
            COALESCE(e.logo, '') AS thumbnail_url
          FROM JOBS j
          LEFT JOIN EMPLOYERS e ON e.id = j.employer_id
          WHERE j.id <> ?
            AND j.status IN ('approved','recruiting','overdue')
            AND (
              j.employer_id = ?
              OR (? <> '' AND j.location LIKE CONCAT('%', ?, '%'))
              OR EXISTS (
                SELECT 1 FROM JOB_CATEGORY_MAP m
                WHERE m.job_id = j.id
                  AND m.category_id IN (SELECT category_id FROM JOB_CATEGORY_MAP WHERE job_id = ?)
              )
            )
          ORDER BY j.created_at DESC, j.id DESC
          LIMIT ?
        ";

        $stmt = $conn->prepare($sql);
        if (!$stmt)
            return [];
        $stmt->bind_param("iissii", $jobId, $employerId, $location, $location, $jobId, $limit);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Attach tags (categories)
        $ids = array_map(fn($r) => (int) $r['id'], $rows);
        $tagsMap = [];
        if (!empty($ids)) {
            $in = implode(",", array_fill(0, count($ids), "?"));
            $inType = str_repeat("i", count($ids));
            $sqlTag = "
              SELECT m.job_id, c.category_name AS name
              FROM JOB_CATEGORY_MAP m
              JOIN JOB_CATEGORIES c ON c.id = m.category_id
              WHERE m.job_id IN ($in)
              ORDER BY c.category_name
            ";
            $stmtT = $conn->prepare($sqlTag);
            if ($stmtT) {
                $stmtT->bind_param($inType, ...$ids);
                $stmtT->execute();
                $rsT = $stmtT->get_result();
                while ($r = $rsT->fetch_assoc())
                    $tagsMap[(int) $r['job_id']][] = $r['name'];
            }
        }
        foreach ($rows as &$r)
            $r['tags'] = $tagsMap[(int) $r['id']] ?? [];
        return $rows;
    }
}

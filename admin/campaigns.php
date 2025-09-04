<?php
require_once '../db.php';
require_once '../auth.php';
require_admin();

// Handle Add / Edit / Delete
$err = $ok = null;
if($_SERVER['REQUEST_METHOD']==='POST'){
    $action = $_POST['action'] ?? '';
    $title = trim($_POST['title'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $organizer = trim($_POST['organizer'] ?? '');

    if($action==='add'){
        if($title && $start_date && $end_date){
            $stmt = $pdo->prepare("INSERT INTO campaigns (title, description, location, start_date, end_date, organizer) VALUES (?,?,?,?,?,?)");
            $stmt->execute([$title, $description, $location, $start_date, $end_date, $organizer]);
            $ok = "Campaign added successfully.";
        } else $err = "Title, start date, and end date are required.";
    } elseif($action==='edit'){
        $id = (int)($_POST['id'] ?? 0);
        if($id && $title && $start_date && $end_date){
            $stmt = $pdo->prepare("UPDATE campaigns SET title=?, description=?, location=?, start_date=?, end_date=?, organizer=? WHERE campaign_id=?");
            $stmt->execute([$title, $description, $location, $start_date, $end_date, $organizer, $id]);
            $ok = "Campaign updated successfully.";
        } else $err = "Title, start date, and end date are required.";
    } elseif($action==='delete'){
        $id = (int)($_POST['id'] ?? 0);
        if($id){
            $pdo->prepare("DELETE FROM campaigns WHERE campaign_id=?")->execute([$id]);
            $ok = "Campaign deleted successfully.";
        }
    }
}

// Fetch all campaigns
$campaigns = $pdo->query("SELECT * FROM campaigns ORDER BY start_date DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Campaigns - BDMS Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"> <style> body { background-color:#f8f9fa; } .navbar { background-color: #dc3545; } .navbar-brand, .navbar-nav .nav-link { color: white !important; } .card-campaign { border-top: 5px solid #dc3545; border-radius:0.75rem; box-shadow:0 5px 15px rgba(0,0,0,0.1); }
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">BDMS Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link active" href="campaigns.php">Campaigns</a></li>
        <li class="nav-item"><a class="nav-link" href="donors.php">Donors</a></li>
        <li class="nav-item"><a class="nav-link" href="alerts.php">Alerts</a></li>
        <li class="nav-item"><a class="nav-link" href="analytics.php">Analytics</a></li>
        <li class="nav-item"><a class="nav-link" href="inventory.php">Inventory</a></li>
        <li class="nav-item"><a class="nav-link" href="requests.php">Requests</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container py-4">
    <h3 class="text-center text-danger mb-4">Manage Campaigns</h3>

    <?php if($err) echo "<div class='alert alert-danger'>$err</div>"; ?>
    <?php if($ok) echo "<div class='alert alert-success'>$ok</div>"; ?>

    <!-- Add Campaign Form -->
    <div class="card p-3 mb-4">
        <h5 class="mb-3">Add New Campaign</h5>
        <form method="post" class="row g-2">
            <input type="hidden" name="action" value="add">
            <div class="col-md-4"><input type="text" name="title" class="form-control" placeholder="Campaign Title" required></div>
            <div class="col-md-3"><input type="text" name="location" class="form-control" placeholder="Location"></div>
            <div class="col-md-2"><input type="date" name="start_date" class="form-control" required></div>
            <div class="col-md-2"><input type="date" name="end_date" class="form-control" required></div>
            <div class="col-md-1"><button class="btn btn-danger w-100">Add</button></div>
            <div class="col-12 mt-2"><textarea name="description" class="form-control" placeholder="Description"></textarea></div>
            <div class="col-12 mt-2"><input type="text" name="organizer" class="form-control" placeholder="Organizer"></div>
        </form>
    </div>

    <!-- Campaigns Table -->
    <div class="card p-4 shadow-sm border-top border-danger mb-4">
    <h5 class="mb-3 text-danger">Manage Campaigns</h5>
    <div class="table-responsive">
        <table class="table table-striped align-middle table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Title</th>
                    <th>Location</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Organizer</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($campaigns as $c): ?>
                <tr>
                    <td><?php echo htmlspecialchars($c['title']); ?></td>
                    <td><?php echo htmlspecialchars($c['location']); ?></td>
                    <td><?php echo date('d M Y', strtotime($c['start_date'])); ?></td>
                    <td><?php echo date('d M Y', strtotime($c['end_date'])); ?></td>
                    <td><?php echo htmlspecialchars($c['organizer']); ?></td>
                    <td class="d-flex gap-1 justify-content-center flex-wrap">
                        <!-- Edit Modal Trigger -->
                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $c['campaign_id']; ?>">
                            <i class="bi bi-pencil-square"></i> Edit
                        </button>
                        <!-- Delete Form -->
                        <form method="post" style="display:inline">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $c['campaign_id']; ?>">
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete campaign?')">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Optional: Edit Modal -->
                <div class="modal fade" id="editModal<?php echo $c['campaign_id']; ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5>Edit Campaign</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form method="post">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="id" value="<?php echo $c['campaign_id']; ?>">
                                    <div class="mb-2">
                                        <input type="text" name="title" value="<?php echo htmlspecialchars($c['title']); ?>" class="form-control" required>
                                    </div>
                                    <div class="mb-2">
                                        <input type="text" name="location" value="<?php echo htmlspecialchars($c['location']); ?>" class="form-control">
                                    </div>
                                    <div class="mb-2">
                                        <input type="date" name="start_date" value="<?php echo $c['start_date']; ?>" class="form-control" required>
                                    </div>
                                    <div class="mb-2">
                                        <input type="date" name="end_date" value="<?php echo $c['end_date']; ?>" class="form-control" required>
                                    </div>
                                    <div class="mb-2">
                                        <input type="text" name="organizer" value="<?php echo htmlspecialchars($c['organizer']); ?>" class="form-control">
                                    </div>
                                    <div class="mb-2">
                                        <textarea name="description" class="form-control"><?php echo htmlspecialchars($c['description']); ?></textarea>
                                    </div>
                                    <button class="btn btn-danger w-100">Save Changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

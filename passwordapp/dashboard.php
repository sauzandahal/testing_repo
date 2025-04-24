<?php
session_start();
require_once 'config/db_connect.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please login to access the dashboard";
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch all passwords for the user
$sql = "SELECT * FROM passwords WHERE user_id = ? ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Manager - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Password Manager</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="auth/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <!-- Success or Error Messages -->
        <?php
        if(isset($_SESSION['success'])) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                ' . $_SESSION['success'] . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            unset($_SESSION['success']);
        }
        
        if(isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                ' . $_SESSION['error'] . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            unset($_SESSION['error']);
        }
        ?>

        <!-- Add New Password Button -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Your Passwords</h2>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPasswordModal">
                <i class="bi bi-plus-circle"></i> Add New Password
            </button>
        </div>

        <!-- Password Cards -->
        <div class="row">
            <?php if($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><?php echo htmlspecialchars($row['platform']); ?></h5>
                                <div>
                                    <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editPasswordModal<?php echo $row['id']; ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deletePasswordModal<?php echo $row['id']; ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="card-text"><strong>Username/Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                                <div class="password-field mb-2">
                                    <strong>Password:</strong>
                                    <div class="input-group">
                                        <input type="password" class="form-control" value="<?php echo htmlspecialchars($row['password']); ?>" readonly id="password<?php echo $row['id']; ?>">
                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password<?php echo $row['id']; ?>">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary copy-password" type="button" data-clipboard-target="#password<?php echo $row['id']; ?>">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                </div>
                                <?php if(!empty($row['pin'])): ?>
                                <div class="pin-field">
                                    <strong>PIN:</strong>
                                    <div class="input-group">
                                        <input type="password" class="form-control" value="<?php echo htmlspecialchars($row['pin']); ?>" readonly id="pin<?php echo $row['id']; ?>">
                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="pin<?php echo $row['id']; ?>">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary copy-password" type="button" data-clipboard-target="#pin<?php echo $row['id']; ?>">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <p class="card-text mt-2"><small class="text-muted">Last Updated: <?php echo date('M d, Y', strtotime($row['updated_at'])); ?></small></p>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Password Modal -->
                    <div class="modal fade" id="editPasswordModal<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Password</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="actions/update_password.php" method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <div class="mb-3">
                                            <label for="platform" class="form-label">Platform/Website</label>
                                            <input type="text" class="form-control" id="platform" name="platform" value="<?php echo htmlspecialchars($row['platform']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Username/Email</label>
                                            <input type="text" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="editPassword<?php echo $row['id']; ?>" name="password" value="<?php echo htmlspecialchars($row['password']); ?>" required>
                                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="editPassword<?php echo $row['id']; ?>">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="pin" class="form-label">PIN (Optional)</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="editPin<?php echo $row['id']; ?>" name="pin" value="<?php echo htmlspecialchars($row['pin']); ?>">
                                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="editPin<?php echo $row['id']; ?>">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Password Modal -->
                    <div class="modal fade" id="deletePasswordModal<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Confirm Delete</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete the password for <strong><?php echo htmlspecialchars($row['platform']); ?></strong>?</p>
                                    <p class="text-danger">This action cannot be undone.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <a href="actions/delete_password.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        <p class="mb-0">You don't have any saved passwords yet. Click the "Add New Password" button to get started.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add Password Modal -->
    <div class="modal fade" id="addPasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="actions/add_password.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="platform" class="form-label">Platform/Website</label>
                            <input type="text" class="form-control" id="platform" name="platform" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Username/Email</label>
                            <input type="text" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="newPassword" name="password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="newPassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-outline-secondary generate-password" type="button">
                                    <i class="bi bi-magic"></i> Generate
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="pin" class="form-label">PIN (Optional)</label>
                            <input type="password" class="form-control" id="pin" name="pin">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.11/dist/clipboard.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
<?php
session_start();
require_once '../config/db_connect.php';

// Fetch statistics
$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$total_products = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$total_sales = $conn->query("SELECT SUM(total_amount) as total FROM orders")->fetch_assoc()['total'] ?? 0;
$recent_products = $conn->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 5");

// Fetch users
$users_query = "SELECT id, name, email, created_at, 
                (SELECT COUNT(*) FROM orders WHERE user_id = users.id) as total_orders 
                FROM users ORDER BY created_at DESC";
$users_result = $conn->query($users_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - EcoFriendly Fashion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #45a049;
            --accent-color: #2E7D32;
            --bg-color: #F5F7FA;
            --text-color: #333;
            --sidebar-width: 260px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: var(--bg-color);
            color: var(--text-color);
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            padding: 2rem;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        .logo i {
            font-size: 2rem;
            color: var(--primary-color);
        }

        .nav-menu {
            list-style: none;
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            color: var(--text-color);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(76, 175, 80, 0.1);
            color: var(--primary-color);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 2rem;
        }

        /* Search Bar */
        .search-bar {
            position: relative;
            margin-bottom: 2rem;
        }

        .search-input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 1px solid #eee;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }

        .search-bar::before {
            content: '\f002';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 1rem;
        }

        .highlight {
            background: rgba(76, 175, 80, 0.2);
            padding: 0.1rem 0.2rem;
            border-radius: 3px;
            font-weight: 500;
        }

        .no-results-message {
            background: white;
            border-radius: 10px;
            margin-top: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .no-results-message p {
            color: #666;
            margin: 0;
        }

        /* Animation for search results */
        tbody tr {
            transition: all 0.3s ease;
        }

        tbody tr:hover {
            background: rgba(76, 175, 80, 0.05);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: rgba(76, 175, 80, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        /* Charts Grid */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .chart-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .chart-header {
            margin-bottom: 1rem;
        }

        /* Products Table */
        .products-table {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .table-header {
            padding: 1.5rem;
            background: #f8f9fa;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f8f9fa;
            font-weight: 600;
        }

        .status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .status.active {
            background: rgba(76, 175, 80, 0.1);
            color: var(--primary-color);
        }

        .action-btn {
            padding: 0.5rem;
            border: none;
            background: none;
            cursor: pointer;
            color: #666;
            transition: color 0.3s ease;
        }

        .action-btn:hover {
            color: var(--primary-color);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                padding: 1rem;
            }

            .logo span, .nav-link span {
                display: none;
            }

            .main-content {
                margin-left: 70px;
            }
        }

        /* Add these styles to your existing CSS */
        .edit-input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.9rem;
            transition: border-color 0.3s ease;
        }

        .edit-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.1);
        }

        .save-edit, .cancel-edit {
            padding: 6px !important;
            margin: 0 2px;
        }

        .save-edit i {
            color: var(--primary-color);
        }

        .cancel-edit i {
            color: #dc3545;
        }

        .action-btn:hover {
            background: rgba(76, 175, 80, 0.1);
        }

        .cancel-edit:hover {
            background: rgba(220, 53, 69, 0.1);
        }

        /* Users Section Styles */
        .users-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-top: 2rem;
            padding: 1.5rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-header h2 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-color);
        }

        .section-header h2 i {
            color: var(--primary-color);
        }

        .users-table-container {
            overflow-x: auto;
        }

        .users-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .users-table th,
        .users-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .users-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #666;
        }

        .users-table tbody tr:hover {
            background: rgba(76, 175, 80, 0.05);
        }

        .status {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status.active {
            background: rgba(76, 175, 80, 0.1);
            color: var(--primary-color);
        }

        .status.blocked {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .action-btn {
            padding: 0.5rem;
            border: none;
            background: none;
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: rgba(76, 175, 80, 0.1);
        }

        .action-btn.block-user:hover {
            background: rgba(220, 53, 69, 0.1);
        }

        .action-btn i {
            font-size: 1rem;
        }

        .view-user i {
            color: #666;
        }

        .block-user i {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <i class="fas fa-leaf"></i>
                <span>EcoFriendly Admin</span>
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="#" class="nav-link active">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-box"></i>
                        <span>Products</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-users"></i>
                        <span>Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-chart-bar"></i>
                        <span>Analytics</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                       
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Search Bar -->
            <div class="search-bar">
                <input type="text" class="search-input" placeholder="Search products, users, or orders...">
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?php echo $total_users; ?></div>
                    <div class="stat-label">Total Users</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                    <div class="stat-value">DT<?php echo number_format($total_sales, 2); ?></div>
                    <div class="stat-label">Total Sales</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?php echo $total_products; ?></div>
                    <div class="stat-label">Total Products</div>
                </div>
            </div>

            <!-- Charts Grid -->
            <div class="charts-grid">
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Sales Analytics</h3>
                    </div>
                    <canvas id="salesChart"></canvas>
                </div>
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Traffic Analytics</h3>
                    </div>
                    <canvas id="trafficChart"></canvas>
                </div>
            </div>

            <!-- Products Table -->
            <div class="products-table">
                <div class="table-header">
                    <h2>Recent Products</h2>
                    
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($product = $recent_products->fetch_assoc()): ?>
                        <tr data-product-id="<?php echo $product['id']; ?>">
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td>DT<?php echo number_format($product['price'], 2); ?></td>
                            <td><span class="status active">Active</span></td>
                            <td>
                                <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                                <button class="action-btn" title="Delete"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Users Section -->
            <div class="users-section">
                <div class="section-header">
                    <h2><i class="fas fa-users"></i> Users Management</h2>
                    <div class="header-actions">
                        <div class="search-bar">
                            <input type="text" id="userSearchInput" class="search-input" placeholder="Search users...">
                        </div>
                    </div>
                </div>

                <div class="users-table-container">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Join Date</th>
                                <th>Orders</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($user = $users_result->fetch_assoc()): ?>
                            <tr data-user-id="<?php echo $user['id']; ?>">
                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                <td><?php echo $user['total_orders']; ?> orders</td>
                                <td><span class="status active">Active</span></td>
                                <td>
                                    <button class="action-btn view-user" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="action-btn block-user" title="Block User">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                    <button class="action-btn delete-user" title="Delete User">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Sales',
                    data: [3000, 4500, 4000, 5500, 5000, 6000],
                    borderColor: '#4CAF50',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(76, 175, 80, 0.1)'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Traffic Chart
        const trafficCtx = document.getElementById('trafficChart').getContext('2d');
        new Chart(trafficCtx, {
            type: 'bar',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Visitors',
                    data: [120, 150, 180, 140, 200, 160, 180],
                    backgroundColor: '#4CAF50'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Replace all the JavaScript code (except the charts) with this:
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality
            const searchInput = document.querySelector('.search-input');
            const productTable = document.querySelector('.products-table tbody');
            
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const rows = productTable.querySelectorAll('tr');
                
                rows.forEach(row => {
                    let matchFound = false;
                    const cells = row.querySelectorAll('td:not(:last-child)');
                    
                    cells.forEach(cell => {
                        const text = cell.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            matchFound = true;
                            if (searchTerm !== '') {
                                const regex = new RegExp(`(${searchTerm})`, 'gi');
                                cell.innerHTML = cell.textContent.replace(
                                    regex, 
                                    '<span class="highlight">$1</span>'
                                );
                            } else {
                                cell.innerHTML = cell.textContent;
                            }
                        }
                    });
                    
                    row.style.display = matchFound || searchTerm === '' ? '' : 'none';
                });
                
                updateNoResultsMessage(productTable, searchTerm);
            });

            function updateNoResultsMessage(table, searchTerm) {
                const noResultsMsg = document.querySelector('.no-results-message');
                const visibleRows = table.querySelectorAll('tr[style=""]').length;
                
                if (visibleRows === 0 && searchTerm !== '') {
                    if (!noResultsMsg) {
                        const message = document.createElement('div');
                        message.className = 'no-results-message';
                        message.innerHTML = `
                            <div style="text-align: center; padding: 2rem;">
                                <i class="fas fa-search" style="font-size: 2rem; color: #ccc; margin-bottom: 1rem;"></i>
                                <p>No products found matching "${searchTerm}"</p>
                            </div>
                        `;
                        table.parentNode.appendChild(message);
                    }
                } else if (noResultsMsg) {
                    noResultsMsg.remove();
                }
            }

            // Product management functionality
            function initializeProductManagement() {
                const rows = document.querySelectorAll('.products-table tbody tr');
                rows.forEach(attachRowEventListeners);
            }

            function attachRowEventListeners(row) {
                const editBtn = row.querySelector('.fa-edit').parentElement;
                const deleteBtn = row.querySelector('.fa-trash').parentElement;

                // Remove existing listeners if any
                editBtn.replaceWith(editBtn.cloneNode(true));
                deleteBtn.replaceWith(deleteBtn.cloneNode(true));

                // Attach new listeners
                row.querySelector('.fa-edit').parentElement.addEventListener('click', () => enableEditMode(row));
                row.querySelector('.fa-trash').parentElement.addEventListener('click', () => handleDelete(row));
            }

            function enableEditMode(row) {
                const productId = row.dataset.productId;
                const productName = row.querySelector('td:first-child').textContent.trim();
                const productPrice = row.querySelector('td:nth-child(2)').textContent.replace('DT', '').trim();
                
                const originalContent = row.innerHTML;
                
                row.innerHTML = `
                    <td>
                        <input type="text" class="edit-input" value="${productName}">
                    </td>
                    <td>
                        <input type="number" step="0.01" class="edit-input" value="${productPrice}">
                    </td>
                    <td><span class="status active">Active</span></td>
                    <td>
                        <button class="action-btn save-edit" title="Save"><i class="fas fa-check"></i></button>
                        <button class="action-btn cancel-edit" title="Cancel"><i class="fas fa-times"></i></button>
                    </td>
                `;

                const saveBtn = row.querySelector('.save-edit');
                const cancelBtn = row.querySelector('.cancel-edit');

                saveBtn.addEventListener('click', () => saveChanges(row, productId));
                cancelBtn.addEventListener('click', () => cancelEdit(row, originalContent));
            }

            async function saveChanges(row, productId) {
                const newName = row.querySelector('input:first-child').value.trim();
                const newPrice = row.querySelector('input:nth-child(2)').value.trim();

                if (!newName || !newPrice) {
                    alert('Please fill in all fields');
                    return;
                }

                try {
                    const response = await fetch('update_product.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `id=${productId}&name=${newName}&price=${newPrice}`
                    });

                    const data = await response.json();
                    if (data.success) {
                        // Update dashboard row
                        restoreRow(row, {
                            id: productId,
                            name: newName,
                            price: newPrice
                        });

                        // Update homepage product if it exists
                        const event = new CustomEvent('productUpdated', {
                            detail: data.product
                        });
                        window.dispatchEvent(event);
                        
                        // Store update in localStorage for homepage sync
                        localStorage.setItem('productUpdate', JSON.stringify({
                            timestamp: Date.now(),
                            product: data.product
                        }));

                        alert('Product updated successfully!');
                    } else {
                        alert(data.message || 'Error updating product');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error updating product');
                }
            }

            function cancelEdit(row, originalContent) {
                row.innerHTML = originalContent;
                attachRowEventListeners(row);
            }

            function restoreRow(row, data) {
                row.innerHTML = `
                    <td>${data.name}</td>
                    <td>DT${parseFloat(data.price).toFixed(2)}</td>
                    <td><span class="status active">Active</span></td>
                    <td>
                        <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                        <button class="action-btn" title="Delete"><i class="fas fa-trash"></i></button>
                    </td>
                `;
                attachRowEventListeners(row);
            }

            async function handleDelete(row) {
                if (confirm('Are you sure you want to delete this product?')) {
                    const productId = row.dataset.productId;

                    try {
                        const response = await fetch('delete_product.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `id=${productId}`
                        });

                        const data = await response.json();
                        if (data.success) {
                            row.remove();
                            alert('Product deleted successfully!');
                        } else {
                            alert(data.message || 'Error deleting product');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Error deleting product');
                    }
                }
            }

            // Initialize product management
            initializeProductManagement();

            // User search functionality
            const userSearchInput = document.getElementById('userSearchInput');
            const userRows = document.querySelectorAll('.users-table tbody tr');

            userSearchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                
                userRows.forEach(row => {
                    const name = row.querySelector('td:first-child').textContent.toLowerCase();
                    const email = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    
                    if (name.includes(searchTerm) || email.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            // User actions
            document.querySelectorAll('.view-user').forEach(btn => {
                btn.addEventListener('click', function() {
                    const userId = this.closest('tr').dataset.userId;
                    viewUserDetails(userId);
                });
            });

            document.querySelectorAll('.block-user').forEach(btn => {
                btn.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const userId = row.dataset.userId;
                    const userName = row.querySelector('td:first-child').textContent;
                    
                    if (confirm(`Are you sure you want to block ${userName}?`)) {
                        toggleUserStatus(userId, row);
                    }
                });
            });

            document.querySelectorAll('.delete-user').forEach(btn => {
                btn.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const userId = row.dataset.userId;
                    const userName = row.querySelector('td:first-child').textContent;
                    
                    if (confirm(`Are you sure you want to delete ${userName}? This action cannot be undone.`)) {
                        deleteUser(userId, row);
                    }
                });
            });
        });

        async function viewUserDetails(userId) {
            try {
                const response = await fetch(`get_user_details.php?id=${userId}`);
                const data = await response.json();
                
                if (data.success) {
                    // Create and show modal with user details
                    showUserDetailsModal(data.user);
                } else {
                    alert('Error fetching user details');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error fetching user details');
            }
        }

        async function toggleUserStatus(userId, row) {
            try {
                const response = await fetch('toggle_user_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `user_id=${userId}`
                });
                
                const data = await response.json();
                if (data.success) {
                    const statusSpan = row.querySelector('.status');
                    const blockBtn = row.querySelector('.block-user');
                    
                    if (data.status === 'blocked') {
                        statusSpan.textContent = 'Blocked';
                        statusSpan.classList.replace('active', 'blocked');
                        blockBtn.title = 'Unblock User';
                    } else {
                        statusSpan.textContent = 'Active';
                        statusSpan.classList.replace('blocked', 'active');
                        blockBtn.title = 'Block User';
                    }
                } else {
                    alert(data.message || 'Error updating user status');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error updating user status');
            }
        }

        async function deleteUser(userId, row) {
            try {
                const response = await fetch('delete_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `user_id=${userId}`
                });
                
                const data = await response.json();
                if (data.success) {
                    row.remove();
                    alert('User deleted successfully');
                } else {
                    alert(data.message || 'Error deleting user');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error deleting user');
            }
        }
    </script>
</body>
</html> 
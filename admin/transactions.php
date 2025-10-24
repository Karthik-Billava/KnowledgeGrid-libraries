
<?php
$page_css = '/KnowledgeGrid-Libraries/admin/css/transactions.css';
include_once '../includes/header.php';
 if (!is_admin_logged_in()): header('Location: /KnowledgeGrid-Libraries/auth/login.php'); exit; endif;
?>
<main>
    <section class="page-content container">
        <h1>Manage Transactions</h1>
        <p>Review, accept, or reject user borrow and purchase requests.</p>

        <div class="table-container">
            <table class="transactions-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Book</th>
                        <th>Library</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Request Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tony Stark</td>
                        <td>The Great Adventure</td>
                        <td>Central City Library</td>
                        <td>Borrow</td>
                        <td><span class="status-tag status-requested">Requested</span></td>
                        <td>2025-10-22 18:30:15</td>
                        <td class="action-cell">
                            <button class="btn btn-accept">Accept</button>
                            <button class="btn btn-reject">Reject</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Jane Doe</td>
                        <td>Mystery of the Old Mansion</td>
                        <td>Coastal Reads Library</td>
                        <td>Purchase</td>
                        <td><span class="status-tag status-requested">Requested</span></td>
                        <td>2025-10-22 17:45:10</td>
                        <td class="action-cell">
                            <button class="btn btn-accept">Accept</button>
                            <button class="btn btn-reject">Reject</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Peter Parker</td>
                        <td>Science Wonders</td>
                        <td>Central City Library</td>
                        <td>Borrow</td>
                        <td><span class="status-tag status-approved">Approved</span></td>
                        <td>2025-10-21 11:05:00</td>
                        <td class="action-cell">
                            <!-- Actions can be disabled or hidden for completed transactions -->
                        </td>
                    </tr>
                    <tr>
                        <td>Bruce Wayne</td>
                        <td>The Art of Painting</td>
                        <td>Downtown Knowledge Hub</td>
                        <td>Borrow</td>
                        <td><span class="status-tag status-rejected">Rejected</span></td>
                        <td>2025-10-20 09:12:30</td>
                        <td class="action-cell"></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </section>
</main>
<?php
require_once '../includes/footer.php';
?>
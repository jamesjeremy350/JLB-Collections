JLB COLLECTION'S - PHP/MySQL shop (updated)

REQUIREMENTS
- XAMPP with Apache and MySQL
- PHP 8+
- MySQL / phpMyAdmin

INSTALLATION
1. Extract the jlb-collections-upgraded folder into C:\\xampp\\htdocs.
2. Start Apache and MySQL in XAMPP.
3. Open http://localhost/phpmyadmin.
4. Import database.sql into phpMyAdmin. For an existing JLB database, run upgrade_delivery_addresses.sql instead to add saved-address support.
5. Open http://localhost/jlb-collections-upgraded/setup_admin.php ONCE. This also ensures the customer name column and saved-address table exist.
6. Confirm that the page says the admin account is ready. Then delete setup_admin.php from the project folder.
7. Open http://localhost/jlb-collections-upgraded/.

ADMIN LOGIN
Gmail: AdminJLB@gmail.com
Password: Admin123J

CUSTOMER FLOW
- Visitors see the welcome page only, with Login (admin) and Sign In (customer registration).
- Sign In creates a customer account using full name, Gmail, password, and confirm password.
- The customer then uses the customer login link to log in.
- Store navigation is visible only after customer login.
- Admin login is separate and leads to the admin dashboard.

DELIVERY ADDRESS
- Checkout saves the customer's recipient name, phone, region, province, city, barangay, street, postal code, and optional landmark.
- The saved address is prefilled the next time the customer checks out.
- The order stores a formatted copy of the delivery address.

PAYMENTS AND TRACKING
- GCash, Maya, and Cash on Delivery are currently payment selections only.
- Live payment collection requires official payment-provider integration.
- Live courier GPS tracking requires a courier API integration.

SECURITY
- Customer and admin passwords are stored using password_hash() and checked with password_verify().
- Delete setup_admin.php after creating/resetting the admin account.
- Change the sample admin password before any public deployment and use HTTPS.

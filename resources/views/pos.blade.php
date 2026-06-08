<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Unipress Press Zambia Limited - Point of Sale System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ============================================ */
        /* CSS RESET & VARIABLES - Line 30-80 */
        /* ============================================ */
        :root {
            --primary-color: #2E7D32;
            --primary-dark: #1B5E20;
            --primary-light: #4CAF50;
            --secondary-color: #1565C0;
            --secondary-dark: #0D47A1;
            --accent-color: #FFC107;
            --accent-dark: #FF8F00;
            --dark-color: #263238;
            --dark-light: #455A64;
            --light-color: #F5F5F5;
            --light-dark: #E0E0E0;
            --danger-color: #E53935;
            --danger-dark: #C62828;
            --success-color: #43A047;
            --success-dark: #2E7D32;
            --warning-color: #FF9800;
            --warning-dark: #F57C00;
            --info-color: #29B6F6;
            --border-radius-sm: 8px;
            --border-radius: 12px;
            --border-radius-lg: 16px;
            --box-shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
            --box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            --box-shadow-lg: 0 16px 32px rgba(0,0,0,0.16);
            --transition-fast: all 0.2s ease;
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            --transition-slow: all 0.5s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e8f0f8 0%, #d4e0ec 50%, #c8d6e5 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        
        /* ============================================ */
        /* ANIMATIONS & FLOATING ELEMENTS - Line 80-150 */
        /* ============================================ */
        @keyframes floatIn {
            0% { transform: translateY(30px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes fadeInUp {
            0% { transform: translateY(20px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes slideInLeft {
            0% { transform: translateX(-30px); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideInRight {
            0% { transform: translateX(30px); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes floatPS {
            0%, 100% { transform: translateY(0) translateX(0) rotate(0deg); opacity: 0.1; }
            25% { transform: translateY(-20px) translateX(15px) rotate(5deg); opacity: 0.15; }
            50% { transform: translateY(-40px) translateX(30px) rotate(10deg); opacity: 0.2; }
            75% { transform: translateY(-20px) translateX(15px) rotate(5deg); opacity: 0.15; }
        }
        
        @keyframes floatObjects {
            0%, 100% { transform: translateY(0) translateX(0); }
            50% { transform: translateY(-50px) translateX(30px); }
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 10%;
            left: 5%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(46,125,50,0.08) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            animation: floatObjects 20s ease-in-out infinite;
            z-index: 0;
        }
        
        body::after {
            content: '';
            position: fixed;
            bottom: 10%;
            right: 5%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(21,101,192,0.08) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            animation: floatObjects 25s ease-in-out infinite reverse;
            z-index: 0;
        }
        
        .floating-ps {
            position: fixed;
            font-weight: 800;
            font-size: 80px;
            color: rgba(46,125,50,0.06);
            pointer-events: none;
            animation: floatPS 20s ease-in-out infinite;
            z-index: 0;
            user-select: none;
        }
        
        /* ============================================ */
        /* CONTAINER & LAYOUT - Line 150-250 */
        /* ============================================ */
        .container {
            position: relative;
            z-index: 10;
            max-width: 1600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .pos-container {
            display: flex;
            gap: 25px;
            min-height: calc(100vh - 140px);
        }
        
        /* Products Section */
        .products-section {
            flex: 2.5;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--box-shadow);
            overflow-y: auto;
            padding: 20px;
            transition: var(--transition);
            animation: slideInLeft 0.5s ease-out;
            border: 1px solid rgba(255,255,255,0.3);
        }
        
        .products-section::-webkit-scrollbar {
            width: 8px;
        }
        
        .products-section::-webkit-scrollbar-track {
            background: var(--light-dark);
            border-radius: 10px;
        }
        
        .products-section::-webkit-scrollbar-thumb {
            background: var(--primary-light);
            border-radius: 10px;
        }
        
        .products-section::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }
        
        /* Cart Section */
        .cart-section {
            flex: 1.2;
            background: white;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--box-shadow-lg);
            padding: 20px;
            display: flex;
            flex-direction: column;
            animation: slideInRight 0.5s ease-out;
            position: sticky;
            top: 20px;
            height: fit-content;
            max-height: calc(100vh - 120px);
        }
        
        /* ============================================ */
        /* HEADER STYLES - Line 250-350 */
        /* ============================================ */
        header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: var(--border-radius-lg);
            padding: 15px 25px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--box-shadow-lg);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
        }
        
        header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            transform: rotate(45deg);
            pointer-events: none;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
            position: relative;
            z-index: 1;
        }
        
        .logo-icon {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            animation: pulse 2s infinite;
        }
        
        .logo-text h1 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .tagline {
            font-size: 0.75rem;
            opacity: 0.9;
            display: block;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
            position: relative;
            z-index: 1;
        }
        
        .user-info {
            background: rgba(255,255,255,0.2);
            padding: 8px 18px;
            border-radius: 50px;
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }
        
        /* ============================================ */
        /* PRODUCT GRID & CARDS - Line 350-500 */
        /* ============================================ */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .product-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 15px;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            box-shadow: var(--box-shadow-sm);
            border: 1px solid rgba(46,125,50,0.1);
        }
        
        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(76,175,80,0.1), transparent);
            transition: 0.5s;
        }
        
        .product-card:hover::before {
            left: 100%;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--box-shadow);
            border-color: var(--primary-light);
        }
        
        .product-image-container {
            width: 100%;
            height: 180px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: var(--border-radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            overflow: hidden;
            position: relative;
        }
        
        .product-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: var(--transition);
        }
        
        .product-card:hover .product-image {
            transform: scale(1.08);
        }
        
        .image-placeholder {
            text-align: center;
            color: #adb5bd;
        }
        
        .image-placeholder i {
            font-size: 48px;
            margin-bottom: 8px;
        }
        
        .product-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: var(--accent-color);
            color: var(--dark-color);
            padding: 3px 8px;
            border-radius: 5px;
            font-size: 10px;
            font-weight: bold;
            z-index: 2;
        }
        
        .product-name {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: var(--dark-color);
        }
        
        .product-price {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--primary-color);
            margin-bottom: 5px;
        }
        
        .product-quantity {
            font-size: 0.8rem;
            color: var(--dark-light);
            display: inline-block;
            padding: 3px 8px;
            background: var(--light-color);
            border-radius: 20px;
        }
        
        .low-stock {
            background: rgba(255,152,0,0.15);
            color: var(--warning-dark);
            font-weight: 600;
        }
        
        .out-of-stock {
            opacity: 0.6;
            pointer-events: none;
            position: relative;
        }
        
        .out-of-stock::after {
            content: 'OUT OF STOCK';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            text-align: center;
            background: rgba(229,57,53,0.9);
            color: white;
            padding: 8px;
            font-size: 12px;
            font-weight: bold;
            transform: translateY(-50%);
            z-index: 3;
        }
        
        /* ============================================ */
        /* CATEGORY TABS - Line 500-580 */
        /* ============================================ */
        .categories {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .category-tab {
            padding: 8px 20px;
            background: white;
            border-radius: 30px;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--primary-color);
            border: 1px solid rgba(46,125,50,0.2);
        }
        
        .category-tab:hover {
            background: var(--primary-light);
            color: white;
            transform: translateY(-2px);
        }
        
        .category-tab.active {
            background: var(--primary-color);
            color: white;
            box-shadow: 0 4px 12px rgba(46,125,50,0.3);
        }
        
        /* ============================================ */
        /* SEARCH BOX - Line 580-630 */
        /* ============================================ */
        .search-box-container {
            position: relative;
            margin-bottom: 20px;
        }
        
        .search-box {
            width: 100%;
            padding: 12px 45px 12px 20px;
            border: 2px solid rgba(46,125,50,0.2);
            border-radius: 50px;
            font-size: 0.9rem;
            transition: var(--transition);
        }
        
        .search-box:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(46,125,50,0.1);
        }
        
        .search-box-container i {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--dark-light);
        }
        
        /* ============================================ */
        /* CART ITEMS STYLES - Line 630-750 */
        /* ============================================ */
        .cart-items {
            flex: 1;
            overflow-y: auto;
            max-height: 400px;
            margin-bottom: 15px;
        }
        
        .cart-items::-webkit-scrollbar {
            width: 6px;
        }
        
        .cart-items::-webkit-scrollbar-track {
            background: var(--light-dark);
            border-radius: 10px;
        }
        
        .cart-items::-webkit-scrollbar-thumb {
            background: var(--primary-light);
            border-radius: 10px;
        }
        
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            margin-bottom: 10px;
            background: var(--light-color);
            border-radius: var(--border-radius-sm);
            transition: var(--transition);
        }
        
        .cart-item:hover {
            background: #e8f5e9;
            transform: translateX(-5px);
        }
        
        .cart-item-info {
            flex: 2;
        }
        
        .cart-item-name {
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 3px;
        }
        
        .cart-item-price {
            font-size: 0.8rem;
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .cart-item-controls {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .quantity-btn {
            width: 30px;
            height: 30px;
            background: white;
            border: 1px solid var(--light-dark);
            border-radius: 5px;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .quantity-btn:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .quantity-input {
            width: 45px;
            text-align: center;
            padding: 5px;
            border: 1px solid var(--light-dark);
            border-radius: 5px;
            font-weight: 500;
        }
        
        .remove-btn {
            color: var(--danger-color);
            cursor: pointer;
            font-size: 20px;
            font-weight: bold;
            transition: var(--transition);
            width: 30px;
            text-align: center;
        }
        
        .remove-btn:hover {
            transform: scale(1.2);
            color: var(--danger-dark);
        }
        
        .empty-cart-message {
            text-align: center;
            padding: 50px 20px;
            color: var(--dark-light);
        }
        
        .empty-cart-message i {
            font-size: 64px;
            margin-bottom: 15px;
            opacity: 0.5;
        }
        
        /* ============================================ */
        /* CART TOTAL & CHECKOUT - Line 750-850 */
        /* ============================================ */
        .cart-total {
            font-size: 1.3rem;
            font-weight: 700;
            text-align: right;
            padding: 15px 0;
            border-top: 2px dashed var(--light-dark);
            border-bottom: 2px dashed var(--light-dark);
            margin: 10px 0;
        }
        
        .cart-total span {
            color: var(--primary-color);
        }
        
        .checkout-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 15px;
        }
        
        .checkout-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(46,125,50,0.4);
        }
        
        .checkout-btn:disabled {
            background: #adb5bd;
            cursor: not-allowed;
        }
        
        /* ============================================ */
        /* MODAL STYLES - Line 850-950 */
        /* ============================================ */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
        }
        
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .modal-content {
            background: white;
            border-radius: var(--border-radius-lg);
            padding: 30px;
            max-width: 500px;
            width: 90%;
            transform: scale(0.9);
            transition: var(--transition);
        }
        
        .modal-overlay.active .modal-content {
            transform: scale(1);
        }
        
        .modal-content h3 {
            margin-bottom: 20px;
            color: var(--primary-color);
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: var(--dark-color);
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--light-dark);
            border-radius: var(--border-radius-sm);
            transition: var(--transition);
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(46,125,50,0.1);
        }
        
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn-primary {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: var(--border-radius-sm);
            cursor: pointer;
            transition: var(--transition);
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: var(--dark-light);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: var(--border-radius-sm);
            cursor: pointer;
            transition: var(--transition);
        }
        
        .btn-secondary:hover {
            background: var(--dark-color);
        }
        
        /* ============================================ */
        /* TIME DISPLAY - Line 950-1000 */
        /* ============================================ */
        .time-display {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(38,50,56,0.95);
            backdrop-filter: blur(10px);
            padding: 10px 25px;
            border-radius: 50px;
            text-align: center;
            z-index: 100;
            box-shadow: var(--box-shadow);
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        #current-time {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--accent-color);
            display: block;
        }
        
        #current-date {
            font-size: 0.7rem;
            color: white;
            display: block;
        }
        
        /* ============================================ */
        /* FLOATING DASHBOARD BUTTON - Line 1000-1050 */
        /* ============================================ */
        .dashboard-float-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            box-shadow: var(--box-shadow-lg);
            transition: var(--transition);
            z-index: 99;
            text-decoration: none;
        }
        
        .dashboard-float-btn:hover {
            transform: scale(1.1) rotate(5deg);
            color: white;
        }
        
        .dashboard-float-tooltip {
            position: absolute;
            right: 70px;
            background: var(--dark-color);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: var(--transition);
        }
        
        .dashboard-float-btn:hover .dashboard-float-tooltip {
            opacity: 1;
        }
        
        /* ============================================ */
        /* ALERTS & LOADING - Line 1050-1100 */
        /* ============================================ */
        .alert-success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            border-left: 4px solid var(--success-color);
            padding: 15px;
            border-radius: var(--border-radius-sm);
            margin-bottom: 15px;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
            border-left: 4px solid var(--danger-color);
            padding: 15px;
            border-radius: var(--border-radius-sm);
            margin-bottom: 15px;
        }
        
        .spinner-border {
            display: inline-block;
            width: 2rem;
            height: 2rem;
            border: 0.25em solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spin 0.75s linear infinite;
        }
        
        .loading-message {
            text-align: center;
            padding: 20px;
            color: var(--primary-color);
            font-weight: 500;
        }
        
        /* ============================================ */
        /* QUANTITY BADGE - Line 1100-1130 */
        /* ============================================ */
        .quantity-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--accent-color);
            color: var(--dark-color);
            border-radius: 50%;
            width: 22px;
            height: 22px;
            font-size: 11px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* ============================================ */
        /* RESPONSIVE DESIGN - Line 1130-1200 */
        /* ============================================ */
        @media (max-width: 1200px) {
            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            }
        }
        
        @media (max-width: 992px) {
            .pos-container {
                flex-direction: column;
            }
            
            .cart-section {
                position: static;
                max-height: none;
            }
            
            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            }
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .logo-text h1 {
                font-size: 1.3rem;
            }
            
            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 12px;
            }
            
            .product-card {
                padding: 10px;
            }
            
            .product-image-container {
                height: 120px;
            }
            
            .time-display {
                top: 10px;
                padding: 5px 15px;
            }
            
            #current-time {
                font-size: 0.9rem;
            }
            
            .dashboard-float-btn {
                width: 45px;
                height: 45px;
                font-size: 18px;
                bottom: 15px;
                right: 15px;
            }
        }
        
        @media (max-width: 576px) {
            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            }
            
            .product-name {
                font-size: 0.8rem;
            }
            
            .product-price {
                font-size: 0.9rem;
            }
            
            .category-tab {
                padding: 5px 12px;
                font-size: 0.75rem;
            }
        }
        
        /* ============================================ */
        /* UTILITY CLASSES - Line 1200-1230 */
        /* ============================================ */
        .animate-bounce {
            animation: bounce 0.5s ease;
        }
        
        .shake {
            animation: shake 0.5s ease;
        }
        
        .fade-in {
            animation: fadeInUp 0.3s ease;
        }
        
        .d-none {
            display: none;
        }
        
        .d-flex {
            display: flex;
        }
        
        .text-center {
            text-align: center;
        }
        
        .mt-2 {
            margin-top: 8px;
        }
        
        .mt-3 {
            margin-top: 15px;
        }
        
        .mb-3 {
            margin-bottom: 15px;
        }
        
        .w-100 {
            width: 100%;
        }
    </style>
</head>
<body>
    <!-- ============================================ -->
    <!-- FLOATING BACKGROUND ELEMENTS - Line 1230-1280 -->
    <!-- ============================================ -->
    <div class="floating-ps" style="top: 5%; left: 3%; animation-delay: 0s;">P</div>
    <div class="floating-ps" style="top: 15%; right: 8%; animation-delay: 2s;">S</div>
    <div class="floating-ps" style="bottom: 10%; left: 10%; animation-delay: 4s;">U</div>
    <div class="floating-ps" style="top: 40%; right: 15%; animation-delay: 1s;">P</div>
    <div class="floating-ps" style="bottom: 20%; right: 5%; animation-delay: 3s;">S</div>
    <div class="floating-ps" style="top: 70%; left: 5%; animation-delay: 5s;">Z</div>
    <div class="floating-ps" style="top: 50%; left: 20%; animation-delay: 2.5s;">M</div>
    <div class="floating-ps" style="bottom: 40%; right: 20%; animation-delay: 1.5s;">W</div>

    <div class="container">
        <!-- ============================================ -->
        <!-- TIME DISPLAY - Line 1280-1310 -->
        <!-- ============================================ -->
        <div class="time-display">
            <span id="current-time"></span>
            <span id="current-date"></span>
        </div>
        
        <!-- ============================================ -->
        <!-- HEADER SECTION - Line 1310-1380 -->
        <!-- ============================================ -->
        <header>
            <div class="logo-container">
                <div class="logo-icon">
                    <i class="fas fa-print"></i>
                </div>
                <div class="logo-text">
                    <h1>
                        <i class="fas fa-building me-2"></i> 
                        Unipress Press Zambia
                    </h1>
                    <span class="tagline">
                        <i class="fas fa-star-of-life"></i> Quality Printing & Publishing Solutions Since 2000
                    </span>
                </div>
                <div class="logo-icon ms-3">
                    <i class="fas fa-receipt"></i>
                </div>
            </div>
            
            <div class="header-right">
                @auth
                <div class="user-info">
                    <i class="fas fa-user-circle fa-lg"></i>
                    <span>{{ Auth::user()->name }}</span>
                    @if(Auth::user()->role === 'admin')
                        <span class="badge" style="background: var(--accent-color); color: var(--dark-color);">Admin</span>
                    @endif
                </div>
                <div class="dropdown">
                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" style="border-radius: 50px;">
                        <i class="fas fa-cog"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @if(Auth::user()->role === 'admin')
                        <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
                        <li><a class="dropdown-item" href="{{ route('products.bulk-edit-quantity') }}"><i class="fas fa-boxes me-2"></i> Manage Products</a></li>
                        <li><hr class="dropdown-divider"></li>
                        @endif
                        <li><a class="dropdown-item" href="{{ route('sales.report') }}"><i class="fas fa-chart-line me-2"></i> Sales Reports</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                @else
                <div class="user-info">
                    <i class="fas fa-user-circle"></i>
                    <span>Guest User</span>
                </div>
                <a href="{{ route('login') }}" class="btn btn-light btn-sm" style="border-radius: 50px;">
                    <i class="fas fa-sign-in-alt me-1"></i> Login
                </a>
                @endauth
            </div>
        </header>
        
        <!-- ============================================ -->
        <!-- MAIN POS CONTAINER - Line 1380-1450 -->
        <!-- ============================================ -->
        <div class="pos-container">
            <!-- PRODUCTS SECTION -->
            <div class="products-section">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">
                        <i class="fas fa-cube me-2" style="color: var(--primary-color);"></i>
                        Products Catalog
                    </h4>
                    <div class="search-box-container">
                        <input type="text" class="search-box" placeholder="Search products by name..." id="searchInput">
                        <i class="fas fa-search"></i>
                    </div>
                </div>

                <!-- Category Tabs -->
                <div class="categories">
                    <div class="category-tab active" data-category="all">
                        <i class="fas fa-th-large me-1"></i> All
                    </div>
                    <div class="category-tab" data-category="drinks">
                        <i class="fas fa-mug-hot me-1"></i> Drinks
                    </div>
                    <div class="category-tab" data-category="food">
                        <i class="fas fa-utensils me-1"></i> Food
                    </div>
                    <div class="category-tab" data-category="snacks">
                        <i class="fas fa-cookie-bite me-1"></i> Snacks
                    </div>
                    @isset($categories)
                        @foreach($categories as $category)
                            @php $lower = strtolower($category); @endphp
                            @if(!in_array($lower, ['all', 'drinks', 'drink', 'food', 'snacks']))
                                <div class="category-tab" data-category="{{ $lower }}">
                                    <i class="fas fa-tag me-1"></i> {{ $category }}
                                </div>
                            @endif
                        @endforeach
                    @endisset
                </div>
                
                <!-- Products Grid -->
                <div class="product-grid" id="productGrid">
                    @forelse($products as $product)
                    <div class="product-card {{ $product->quantity <= 0 ? 'out-of-stock' : '' }}" 
                         data-id="{{ $product->id }}" 
                         data-category="{{ strtolower(trim($product->category->name ?? $product->category ?? 'other')) }}"
                         data-name="{{ strtolower($product->name) }}"
                         data-price="{{ $product->price }}">
                        
                        @auth
                            @if(Auth::user()->role === 'admin')
                                <span class="product-badge">
                                    <i class="fas fa-hashtag"></i> {{ $product->id }}
                                </span>
                            @endif
                        @endauth
                        
                        <!-- Product Image - FIXED FOR INFINITYFREE -->
                        <div class="product-image-container">
                            @php
                                $imageExists = false;
                                $imageUrl = null;
                                
                                if($product->image && !empty($product->image)) {
                                    // Extract just the filename
                                    $filename = basename($product->image);
                                    // Build the correct URL path for InfinityFree
                                    $imageUrl = '/uploads/product_images/' . $filename;
                                    $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/product_images/' . $filename;
                                    $imageExists = file_exists($fullPath);
                                }
                            @endphp
                            
                            @if($imageExists && $imageUrl)
                                <img src="{{ $imageUrl }}" 
                                     alt="{{ $product->name }}" 
                                     class="product-image"
                                     loading="lazy"
                                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'image-placeholder\'><i class=\'fas fa-box-open fa-3x\'></i><div class=\'mt-2\'>{{ Str::limit($product->name, 15) }}</div></div>'">
                            @else
                                <div class="image-placeholder">
                                    <i class="fas fa-box-open fa-3x"></i>
                                    <div class="mt-2">{{ Str::limit($product->name, 15) }}</div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="product-name" title="{{ $product->name }}">
                            {{ Str::limit($product->name, 25) }}
                        </div>
                        <div class="product-price">
                            <i class="fas fa-money-bill-wave me-1" style="font-size: 0.8rem;"></i>
                            ZMW {{ number_format($product->price, 2) }}
                        </div>
                        <div class="product-quantity {{ $product->quantity <= 5 ? 'low-stock' : '' }}">
                            <i class="fas fa-boxes me-1"></i>
                            Stock: {{ $product->quantity }}
                        </div>
                    </div>
                    @empty
                    <div class="text-center p-5">
                        <i class="fas fa-box-open fa-4x mb-3" style="color: var(--dark-light);"></i>
                        <p>No products found. Please add products to get started.</p>
                    </div>
                    @endforelse
                </div>
            </div>
            
            <!-- ============================================ -->
            <!-- CART SECTION - Line 1450-1550 -->
            <!-- ============================================ -->
            <div class="cart-section">
                <h4 class="mb-3">
                    <i class="fas fa-shopping-cart me-2" style="color: var(--primary-color);"></i>
                    Sales Cart
                    <span id="cartItemCount" class="badge" style="background: var(--accent-color); color: var(--dark-color); margin-left: 8px;">0</span>
                </h4>
                
                <!-- Cart Items List -->
                <div class="cart-items" id="cartItems">
                    <div class="empty-cart-message">
                        <i class="fas fa-shopping-cart"></i>
                        <p>Your cart is empty</p>
                        <small class="text-muted">Click on products to add them to the cart</small>
                    </div>
                </div>
                
                <!-- Cart Summary -->
                <div class="cart-summary">
                    <div class="cart-total">
                        <i class="fas fa-calculator me-2"></i>
                        Total: <span id="cartTotal">ZMW 0.00</span>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-money-bill-wave me-1"></i> Amount Paid (ZMW)
                        </label>
                        <input type="number" step="0.01" min="0" id="amountPaid" class="form-control" placeholder="Enter amount customer paid">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-exchange-alt me-1"></i> Change
                        </label>
                        <div class="alert alert-info mb-0" id="changeDisplay" style="background: #e3f2fd;">
                            <strong id="changeAmount">ZMW 0.00</strong>
                        </div>
                    </div>
                </div>
                
                <!-- Loading Indicator -->
                <div id="checkoutLoading" style="display: none;">
                    <div class="loading-message">
                        <div class="spinner-border text-success mb-2"></div>
                        <p>Processing transaction...</p>
                    </div>
                </div>
                
                <!-- Checkout Result -->
                <div id="checkoutResult"></div>
                
                <!-- Action Buttons -->
                <div class="cart-footer">
                    @auth
                        <button class="checkout-btn" id="checkoutBtn">
                            <i class="fas fa-check-circle fa-lg"></i>
                            Process Payment
                        </button>
                    @else
                        <button class="checkout-btn" disabled>
                            <i class="fas fa-lock fa-lg"></i>
                            Login to Process Payment
                        </button>
                    @endauth
                    
                    <button class="btn btn-secondary w-100 mt-2" id="clearCartBtn" style="display: none;">
                        <i class="fas fa-trash-alt me-2"></i>
                        Clear Cart
                    </button>
                    
                    <button class="btn btn-secondary w-100 mt-2" id="reprintBtn" style="display: none;">
                        <i class="fas fa-print me-2"></i>
                        Reprint Last Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ============================================ -->
    <!-- FLOATING DASHBOARD BUTTON - Line 1550-1580 -->
    <!-- ============================================ -->
    @auth
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('dashboard') }}" class="dashboard-float-btn animate__animated animate__pulse animate__infinite">
            <i class="fas fa-tachometer-alt"></i>
            <span class="dashboard-float-tooltip">Admin Dashboard</span>
        </a>
        @endif
    @endauth
    
    <!-- ============================================ -->
    <!-- CUSTOMER MODAL - Line 1580-1650 -->
    <!-- ============================================ -->
    <div class="modal-overlay" id="customerModal">
        <div class="modal-content">
            <h3>
                <i class="fas fa-user-check me-2" style="color: var(--primary-color);"></i>
                Complete Sale
            </h3>
            <form id="customerForm">
                <div class="form-group">
                    <label for="customerName">
                        <i class="fas fa-user me-1"></i> Customer Name
                    </label>
                    <input type="text" id="customerName" class="form-control" placeholder="Walk-in Customer">
                </div>
                <div class="form-group">
                    <label for="customerPhone">
                        <i class="fas fa-phone me-1"></i> Phone Number (Optional)
                    </label>
                    <input type="tel" id="customerPhone" class="form-control" placeholder="095xxxxxxx">
                </div>
                <div class="form-group">
                    <label for="modalAmountPaid">
                        <i class="fas fa-money-bill-wave me-1"></i> Amount Paid (ZMW)
                    </label>
                    <input type="number" step="0.01" id="modalAmountPaid" class="form-control" placeholder="Enter amount paid" required>
                </div>
                <div class="form-group">
                    <label for="paymentMethod">
                        <i class="fas fa-credit-card me-1"></i> Payment Method
                    </label>
                    <select id="paymentMethod" class="form-control" required>
                        <option value="Cash">💵 Cash</option>
                        <option value="Mobile Money">📱 Mobile Money</option>
                        <option value="Card">💳 Card</option>
                        <option value="Bank Transfer">🏦 Bank Transfer</option>
                        <option value="Credit">📝 Credit</option>
                    </select>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" id="cancelCheckout">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check-circle me-1"></i> Complete Sale
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- SCRIPTS - Line 1650-2046 -->
    <!-- ============================================ -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // ============================================
        // GLOBAL VARIABLES - Line 1660-1675
        // ============================================
        let lastSaleId = null;
        let cartQuantity = 0;
        let currentCart = {};
        
        // ============================================
        // UTILITY FUNCTIONS - Line 1675-1700
        // ============================================
        function formatCurrency(amount) {
            return `ZMW ${parseFloat(amount).toFixed(2)}`;
        }
        
        function updateCartBadge() {
            $('#cartItemCount').text(cartQuantity);
            if (cartQuantity > 0) {
                $('#clearCartBtn').show();
            } else {
                $('#clearCartBtn').hide();
            }
        }
        
        // ============================================
        // TIME DISPLAY FUNCTION - Line 1700-1720
        // ============================================
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-ZM', { hour12: true });
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateString = now.toLocaleDateString('en-ZM', options);
            document.getElementById('current-time').textContent = timeString;
            document.getElementById('current-date').textContent = dateString;
        }
        updateTime();
        setInterval(updateTime, 1000);
        
        // ============================================
        // AMOUNT PAID & CHANGE CALCULATION - Line 1720-1740
        // ============================================
        $('#amountPaid, #modalAmountPaid').on('input', function() {
            let total = parseFloat($('#cartTotal').text().replace('ZMW', '').trim()) || 0;
            let paid = parseFloat($('#amountPaid').val()) || 0;
            let change = paid - total;
            if (change >= 0) {
                $('#changeAmount').text(formatCurrency(change));
                $('#changeDisplay').removeClass('alert-danger').addClass('alert-info');
            } else {
                $('#changeAmount').text('Insufficient Amount');
                $('#changeDisplay').removeClass('alert-info').addClass('alert-danger');
            }
        });
        
        // ============================================
        // CART UPDATE FUNCTIONS - Line 1740-1800
        // ============================================
        function updateCartItem(productId, quantity) {
            $.post('{{ route("pos.updateCart") }}', {
                product_id: productId,
                quantity: quantity,
                _token: '{{ csrf_token() }}'
            }, function(response) {
                if(response.success) {
                    updateCartDisplay(response.cart);
                    updateCartQuantity(response.cart);
                }
            }).fail(function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.error || 'Failed to update quantity',
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        }
        
        function updateCartDisplay(cart = null) {
            const $cartItems = $('#cartItems');
            let total = 0;
            
            if(!cart || Object.keys(cart).length === 0) {
                $cartItems.html(`
                    <div class="empty-cart-message">
                        <i class="fas fa-shopping-cart"></i>
                        <p>Your cart is empty</p>
                        <small class="text-muted">Click on products to add them to the cart</small>
                    </div>
                `);
                $('#cartTotal').text(formatCurrency(0));
                $('#amountPaid').val('');
                $('#changeAmount').text(formatCurrency(0));
                return;
            }
            
            $cartItems.empty();
            Object.values(cart).forEach(item => {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;
                $cartItems.append(`
                    <div class="cart-item fade-in">
                        <div class="cart-item-info">
                            <div class="cart-item-name">${escapeHtml(item.name)}</div>
                            <div class="cart-item-price">${formatCurrency(item.price)}</div>
                            <small>Total: ${formatCurrency(itemTotal)}</small>
                        </div>
                        <div class="cart-item-controls">
                            <button class="quantity-btn minus" data-id="${item.id}">-</button>
                            <input type="number" class="quantity-input" value="${item.quantity}" min="1" data-id="${item.id}" style="width: 50px;">
                            <button class="quantity-btn plus" data-id="${item.id}">+</button>
                            <span class="remove-btn" data-id="${item.id}" title="Remove item">&times;</span>
                        </div>
                    </div>
                `);
            });
            $('#cartTotal').text(formatCurrency(total));
            updateCartQuantity(cart || {});
            
            // Trigger change calculation
            $('#amountPaid').trigger('input');
        }
        
        function updateCartQuantity(cart) {
            cartQuantity = Object.values(cart).reduce((total, item) => total + item.quantity, 0);
            updateCartBadge();
        }
        
        function escapeHtml(text) {
            return text.replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            });
        }
        
        // ============================================
        // FILTER PRODUCTS FUNCTION - Line 1800-1830
        // ============================================
        function filterProducts(category, searchTerm = '') {
            category = category.toLowerCase();
            searchTerm = searchTerm.toLowerCase();
            $('.product-card').each(function() {
                const $card = $(this);
                const cardCategory = ($card.data('category') || '').toString().toLowerCase();
                const cardName = ($card.data('name') || '').toString().toLowerCase();
                const categoryMatch = category === 'all' || cardCategory === category;
                const searchMatch = searchTerm === '' || cardName.includes(searchTerm);
                $card.toggle(categoryMatch && searchMatch);
            });
        }
        
        // ============================================
        // PLAY SOUND FUNCTION - Line 1830-1850
        // ============================================
        function playSound(type) {
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                oscillator.type = 'sine';
                oscillator.frequency.value = type === 'success' ? 880 : 440;
                gainNode.gain.value = 0.3;
                oscillator.start();
                gainNode.gain.exponentialRampToValueAtTime(0.00001, audioContext.currentTime + 0.5);
                oscillator.stop(audioContext.currentTime + 0.3);
            } catch(e) {
                console.log('Audio not supported');
            }
        }
        
        // ============================================
        // DOCUMENT READY - MAIN INITIALIZATION - Line 1850-2046
        // ============================================
        $(document).ready(function() {
            updateCartDisplay();
            
            // ========================================
            // ADD TO CART - Line 1855-1890
            // ========================================
            $('.product-card:not(.out-of-stock)').on('click', function(e) {
                const $card = $(this);
                $card.addClass('animate-bounce');
                setTimeout(() => $card.removeClass('animate-bounce'), 500);
                
                const productId = $(this).data('id');
                const productName = $(this).find('.product-name').text();
                
                $.post('{{ route("pos.addToCart") }}', {
                    product_id: productId,
                    _token: '{{ csrf_token() }}'
                }, function(response) {
                    if(response.success) {
                        updateCartDisplay(response.cart);
                        updateCartQuantity(response.cart);
                        
                        const $quantityDisplay = $(`.product-card[data-id="${productId}"] .product-quantity`);
                        $quantityDisplay.html(`<i class="fas fa-boxes me-1"></i>Stock: ${response.product.quantity}`);
                        $quantityDisplay.toggleClass('low-stock', response.product.quantity <= 5);
                        
                        if(response.product.quantity <= 0) {
                            $(`.product-card[data-id="${productId}"]`).addClass('out-of-stock');
                        }
                        
                        // Show brief success indication
                        const $priceDisplay = $(`.product-card[data-id="${productId}"] .product-price`);
                        $priceDisplay.css('transform', 'scale(1.05)');
                        setTimeout(() => $priceDisplay.css('transform', ''), 300);
                    }
                }).fail(function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.error || 'Failed to add product to cart',
                        timer: 2000,
                        showConfirmButton: false
                    });
                });
            });
            
            // ========================================
            // QUANTITY CONTROLS - Line 1890-1930
            // ========================================
            $(document).on('click', '.quantity-btn.minus', function() {
                const productId = $(this).data('id');
                const input = $(this).siblings('.quantity-input');
                const newQuantity = parseInt(input.val()) - 1;
                if(newQuantity >= 1) {
                    updateCartItem(productId, newQuantity);
                } else {
                    $(this).addClass('shake');
                    setTimeout(() => $(this).removeClass('shake'), 500);
                }
            });
            
            $(document).on('click', '.quantity-btn.plus', function() {
                const productId = $(this).data('id');
                const input = $(this).siblings('.quantity-input');
                const newQuantity = parseInt(input.val()) + 1;
                updateCartItem(productId, newQuantity);
            });
            
            $(document).on('change', '.quantity-input', function() {
                const productId = $(this).data('id');
                let newQuantity = parseInt($(this).val()) || 1;
                if(newQuantity > 0) {
                    updateCartItem(productId, newQuantity);
                } else {
                    $(this).val(1);
                    $(this).addClass('shake');
                    setTimeout(() => $(this).removeClass('shake'), 500);
                }
            });
            
            // ========================================
            // REMOVE FROM CART - Line 1930-1955
            // ========================================
            $(document).on('click', '.remove-btn', function() {
                const productId = $(this).data('id');
                const $card = $(`.product-card[data-id="${productId}"]`);
                
                $.post('{{ route("pos.removeFromCart") }}', {
                    product_id: productId,
                    _token: '{{ csrf_token() }}'
                }, function(response) {
                    updateCartDisplay(response.cart);
                    updateCartQuantity(response.cart);
                    
                    if(response.product && response.product.quantity > 0) {
                        $card.removeClass('out-of-stock');
                        $card.find('.product-quantity').html(`<i class="fas fa-boxes me-1"></i>Stock: ${response.product.quantity}`);
                        $card.find('.product-quantity').toggleClass('low-stock', response.product.quantity <= 5);
                    }
                });
            });
            
            // ========================================
            // CLEAR CART BUTTON - Line 1955-1980
            // ========================================
            $('#clearCartBtn').on('click', function() {
                if (cartQuantity === 0) return;
                
                Swal.fire({
                    title: 'Clear Cart?',
                    text: 'Are you sure you want to remove all items from the cart?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2E7D32',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, clear it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('{{ route("pos.clearCart") }}', {
                            _token: '{{ csrf_token() }}'
                        }, function(response) {
                            updateCartDisplay({});
                            updateCartQuantity({});
                            Swal.fire({
                                icon: 'success',
                                title: 'Cart Cleared',
                                text: 'All items have been removed from the cart',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            playSound('success');
                        });
                    }
                });
            });
            
            // ========================================
            // CHECKOUT PROCESS - Line 1980-2030
            // ========================================
            $('#checkoutBtn').on('click', function() {
                if (cartQuantity === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Empty Cart',
                        text: 'Your cart is empty. Add products to continue.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                }
                
                let total = parseFloat($('#cartTotal').text().replace('ZMW', '').trim()) || 0;
                let paid = parseFloat($('#amountPaid').val()) || 0;
                
                if (paid < total) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Insufficient Payment',
                        text: `Amount paid (${formatCurrency(paid)}) is less than total (${formatCurrency(total)}). Please enter sufficient amount.`,
                        confirmButtonColor: '#2E7D32'
                    });
                    return;
                }
                
                $('#modalAmountPaid').val(paid);
                $('#customerModal').addClass('active');
                $('#customerName').focus();
            });
            
            $('#cancelCheckout').on('click', function() {
                $('#customerModal').removeClass('active');
                $('#customerForm')[0].reset();
            });
            
            $('#customerForm').on('submit', function(e) {
                e.preventDefault();
                $('#customerModal').removeClass('active');
                
                const customerName = $('#customerName').val().trim() || 'Walk-in Customer';
                const phone = $('#customerPhone').val().trim() || null;
                const amountPaid = parseFloat($('#modalAmountPaid').val()) || 0;
                const paymentMethod = $('#paymentMethod').val();
                
                $('#checkoutLoading').show();
                $('#checkoutBtn').prop('disabled', true);
                $('#checkoutResult').empty();
                
                $.post('{{ route("pos.checkout") }}', {
                    _token: '{{ csrf_token() }}',
                    customer_name: customerName,
                    phone: phone,
                    amount_paid: amountPaid,
                    payment_method: paymentMethod
                }, function(response) {
                    $('#checkoutLoading').hide();
                    $('#checkoutBtn').prop('disabled', false);
                    
                    if(response.success) {
                        $('#cartItems').html(`
                            <div class="empty-cart-message">
                                <i class="fas fa-shopping-cart"></i>
                                <p>Your cart is empty</p>
                                <small class="text-muted">Click on products to add them to the cart</small>
                            </div>
                        `);
                        $('#cartTotal').text('ZMW 0.00');
                        cartQuantity = 0;
                        updateCartBadge();
                        
                        lastSaleId = response.sale_id;
                        $('#reprintBtn').show();
                        $('#clearCartBtn').hide();
                        
                        const successHtml = `
                            <div id="successMessage" class="alert alert-success animate__animated animate__fadeIn">
                                <h5><i class="fas fa-check-circle me-2"></i>Sale Completed Successfully!</h5>
                                <p><strong>Receipt #:</strong> ${response.receipt_number}</p>
                                <p><strong>Customer:</strong> ${escapeHtml(customerName)}</p>
                                <p><strong>Amount Paid:</strong> ${formatCurrency(amountPaid)}</p>
                                <p><strong>Payment Method:</strong> ${paymentMethod}</p>
                            </div>
                            <div class="mt-3 text-center">
                                <button class="btn btn-primary" id="printBtn">
                                    <i class="fas fa-print me-2"></i> Print Receipt
                                </button>
                            </div>
                        `;
                        $('#checkoutResult').html(successHtml);
                        
                        setTimeout(() => {
                            $('#successMessage').fadeOut('slow', function() { $(this).remove(); });
                        }, 5000);
                        
                        $('#customerName').val('');
                        $('#customerPhone').val('');
                        $('#amountPaid').val('');
                        $('#modalAmountPaid').val('');
                        $('#paymentMethod').val('Cash');
                        $('#changeAmount').text(formatCurrency(0));
                        
                        playSound('success');
                    } else {
                        $('#checkoutResult').html(`
                            <div class="alert alert-danger animate__animated animate__fadeIn">
                                <h5><i class="fas fa-exclamation-circle me-2"></i>Error Processing Sale</h5>
                                <p>${response.message || 'Please try again'}</p>
                            </div>
                        `);
                        playSound('error');
                        setTimeout(() => $('.alert-danger').fadeOut(), 5000);
                    }
                }).fail(function(xhr) {
                    $('#checkoutLoading').hide();
                    $('#checkoutBtn').prop('disabled', false);
                    $('#checkoutResult').html(`
                        <div class="alert alert-danger animate__animated animate__fadeIn">
                            <h5><i class="fas fa-exclamation-circle me-2"></i>Server Error</h5>
                            <p>${xhr.responseJSON?.message || 'Please contact support'}</p>
                        </div>
                    `);
                    playSound('error');
                    setTimeout(() => $('.alert-danger').fadeOut(), 5000);
                });
            });
            
            // ========================================
            // PRINT RECEIPT - Line 2030-2040
            // ========================================
            $(document).on('click', '#printBtn', function() {
                if (lastSaleId) {
                    window.open('/pos/receipt/' + lastSaleId, '_blank');
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Receipt Found',
                        text: 'Unable to print receipt. Please try again.',
                        timer: 2000
                    });
                }
            });
            
            $('#reprintBtn').on('click', function() {
                if (lastSaleId) {
                    window.open('/pos/receipt/' + lastSaleId, '_blank');
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Receipt Found',
                        text: 'No recent receipt available for reprinting',
                        timer: 2000
                    });
                }
            });
            
            // ========================================
            // CATEGORY FILTER - Line 2040-2046
            // ========================================
            $('.category-tab').on('click', function() {
                $('.category-tab').removeClass('active');
                $(this).addClass('active');
                filterProducts($(this).data('category'));
            });
            
            $('#searchInput').on('input', function() {
                filterProducts($('.category-tab.active').data('category'), $(this).val().toLowerCase());
            });
            
            $('#searchInput').on('keydown', function(e) {
                if (e.key === 'Enter') e.preventDefault();
            });
        });
    </script>
</body>
</html>
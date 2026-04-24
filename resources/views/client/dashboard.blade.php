<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LaundryHub Pro | Student Dashboard</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Leaflet Maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8fafc;
            color: #0f172a;
            line-height: 1.5;
            overflow-x: hidden;
        }

        :root {
            --primary: #0f3b2c;
            --primary-dark: #0a2c20;
            --primary-light: #e8f3ef;
            --secondary: #2c7a4b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --gray-50: #f9fafb;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --radius-lg: 1rem;
            --radius-xl: 1.25rem;
            --radius-2xl: 1.5rem;
        }

        .container {
            width: 100%;
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        @media (max-width: 768px) {
            .container { padding: 0 1rem; }
        }

        .navbar {
            background: white;
            border-bottom: 1px solid var(--gray-200);
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.98);
        }
        
        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--primary);
        }
        
        .navbar-brand i {
            font-size: 1.5rem;
        }
        
        .navbar-links {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .nav-link {
            padding: 0.5rem 1rem;
            text-decoration: none;
            color: var(--gray-600);
            font-weight: 500;
            font-size: 0.875rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .nav-link:hover {
            background: var(--gray-100);
            color: var(--primary);
        }
        
        .nav-link.active {
            background: var(--primary-light);
            color: var(--primary);
        }
        
        .nav-link i {
            font-size: 1rem;
        }
        
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--gray-600);
            cursor: pointer;
            padding: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .navbar-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                flex-direction: column;
                padding: 1rem;
                border-bottom: 1px solid var(--gray-200);
                box-shadow: var(--shadow-md);
            }
            
            .navbar-links.active {
                display: flex;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .nav-link {
                width: 100%;
                padding: 0.75rem 1rem;
            }
        }

        .dashboard-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        
        .header-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.25rem;
        }
        
        .header-title h1 {
            font-size: clamp(1.5rem, 4vw, 2rem);
            font-weight: 700;
            color: white;
            margin-bottom: 0.25rem;
        }
        
        .header-title p {
            color: rgba(255, 255, 255, 0.85);
            font-size: clamp(0.875rem, 3vw, 1rem);
        }
        
        .wallet-badge {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            padding: 0.625rem 1.25rem;
            border-radius: 3rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.625rem;
            color: white;
        }
        
        .logout-btn {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            padding: 0.625rem 1.25rem;
            border-radius: 3rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.625rem;
            color: white;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
        }
        
        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.3);
            border-color: rgba(239, 68, 68, 0.5);
            transform: translateY(-1px);
        }

        .student-info-banner {
            background: linear-gradient(135deg, #e8f3ef 0%, #d1fae5 100%);
            border-radius: var(--radius-lg);
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .student-details {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            align-items: center;
        }
        
        .student-detail-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            background: white;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            box-shadow: var(--shadow-sm);
        }
        
        .student-detail-item i {
            color: var(--primary);
            font-size: 1rem;
        }
        
        .edit-student-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }
        
        .edit-student-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1001;
            align-items: center;
            justify-content: center;
        }
        
        .modal.active {
            display: flex;
        }
        
        .modal-content {
            background: white;
            border-radius: var(--radius-2xl);
            max-width: 550px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalSlideIn 0.3s ease;
        }
        
        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .modal-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-header h3 {
            font-size: 1.125rem;
            font-weight: 600;
        }
        
        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--gray-400);
            transition: color 0.2s;
        }
        
        .close-modal:hover {
            color: var(--danger);
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--gray-200);
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        .referral-banner {
            background: linear-gradient(135deg, #fffbeb 0%, #fef9e3 100%);
            border-left: 4px solid var(--warning);
            border-radius: var(--radius-lg);
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .referral-code-box {
            background: white;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            border: 1px solid #fed7aa;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .referral-code {
            font-family: monospace;
            font-weight: 700;
            font-size: 0.875rem;
        }
        
        .copy-btn {
            background: #fef3c7;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.2s;
            color: #b45309;
        }
        
        .copy-btn:hover {
            background: #fed7aa;
            transform: scale(1.05);
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.75rem;
        }
        
        @media (max-width: 768px) {
            .dashboard-grid { grid-template-columns: 1fr; }
        }

        .form-card {
            background: white;
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--gray-200);
            overflow: hidden;
        }
        
        .card-header {
            padding: 1.25rem 1.75rem;
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
        }
        
        .card-header h4, .card-header h5 {
            font-size: 1.125rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0;
        }
        
        .card-body {
            padding: 1.75rem;
        }

        .step-wrapper {
            margin-bottom: 2rem;
            overflow-x: auto;
        }
        
        .step-indicator {
            display: flex;
            justify-content: space-between;
            position: relative;
            min-width: 450px;
        }
        
        .step-indicator::before {
            content: '';
            position: absolute;
            top: 1.5rem;
            left: 2rem;
            right: 2rem;
            height: 2px;
            background: var(--gray-200);
            z-index: 1;
        }
        
        .step {
            text-align: center;
            position: relative;
            z-index: 2;
            flex: 1;
            cursor: pointer;
        }
        
        .step-number {
            width: 3rem;
            height: 3rem;
            background: white;
            border: 2px solid var(--gray-200);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            font-weight: 700;
            color: var(--gray-500);
            transition: all 0.2s;
        }
        
        .step.active .step-number {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
            box-shadow: 0 0 0 4px rgba(15, 59, 44, 0.15);
        }
        
        .step.active .step-label {
            color: var(--primary);
            font-weight: 600;
        }
        
        .step.completed .step-number {
            background: var(--success);
            border-color: var(--success);
            color: white;
        }
        
        .step-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--gray-500);
        }

        .map-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        @media (max-width: 768px) {
            .map-section { grid-template-columns: 1fr; }
        }
        
        .map-card {
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            overflow: hidden;
            background: white;
        }
        
        .map-card h6 {
            padding: 0.75rem 1rem;
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
            margin: 0;
            font-size: 0.875rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .map-container {
            height: 250px;
            width: 100%;
            background: #eef2ff;
        }
        
        .search-box {
            position: relative;
            margin: 0.75rem 1rem;
        }
        
        .search-box input {
            width: 100%;
            padding: 0.625rem 1rem 0.625rem 2.5rem;
            border: 1px solid var(--gray-200);
            border-radius: 0.75rem;
            font-size: 0.875rem;
        }
        
        .search-box input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(15, 59, 44, 0.1);
        }
        
        .search-box i {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
        }
        
        .location-select-wrapper {
            margin: 0.5rem 1rem;
        }
        
        .location-select {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1px solid var(--gray-200);
            border-radius: 0.75rem;
            font-size: 0.875rem;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .location-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(15, 59, 44, 0.1);
        }
        
        .location-select optgroup {
            font-weight: 600;
            color: var(--primary);
        }
        
        .location-select option {
            padding: 0.5rem;
        }
        
        .manage-locations-link {
            display: block;
            text-align: center;
            margin-top: 0.5rem;
            font-size: 0.75rem;
            color: var(--primary);
            text-decoration: none;
        }
        
        .manage-locations-link:hover {
            text-decoration: underline;
        }

        .room-details-card {
            background: var(--primary-light);
            border-radius: var(--radius-lg);
            padding: 1rem;
            margin: 0.5rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
        }
        
        .room-details-card i {
            color: var(--primary);
            font-size: 1.125rem;
        }
        
        .auto-locate-badge {
            background: linear-gradient(135deg, #dbeafe, #eff6ff);
            border-radius: 0.5rem;
            padding: 0.75rem;
            margin: 0.5rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
            color: var(--primary);
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid #bfdbfe;
        }
        
        .auto-locate-badge:hover {
            background: linear-gradient(135deg, #bfdbfe, #dbeafe);
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }
        
        .auto-locate-badge i {
            font-size: 1.125rem;
        }

        .basket-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .basket-card {
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s ease;
            background: white;
        }
        
        .basket-card:hover {
            border-color: var(--primary);
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }
        
        .basket-card.selected {
            border-color: var(--primary);
            background: var(--primary-light);
        }
        
        .basket-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
        }
        
        .basket-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            margin: 0.5rem 0;
        }

        .student-discount-badge {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border-radius: 0.5rem;
            padding: 0.5rem;
            text-align: center;
            font-size: 0.75rem;
            font-weight: 600;
            color: #92400e;
            margin-top: 0.5rem;
        }

        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .item-card {
            border: 1px solid var(--gray-200);
            border-radius: 0.75rem;
            padding: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
            background: white;
        }
        
        .item-card:hover {
            border-color: var(--primary);
            background: var(--gray-50);
        }
        
        .item-card.selected {
            background: var(--primary-light);
            border-color: var(--primary);
        }
        
        .item-checkbox {
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid var(--gray-300);
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
        }
        
        .item-card.selected .item-checkbox {
            background: var(--primary);
            border-color: var(--primary);
        }
        
        .item-card.selected .item-checkbox i {
            color: white;
            font-size: 0.75rem;
            display: block !important;
        }

        .price-summary {
            background: linear-gradient(135deg, var(--gray-800) 0%, var(--gray-900) 100%);
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            color: white;
            margin-bottom: 1.5rem;
        }
        
        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .price-total {
            display: flex;
            justify-content: space-between;
            padding-top: 0.75rem;
            font-weight: 700;
            font-size: 1.125rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .stat-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1rem;
            border: 1px solid var(--gray-200);
            transition: all 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
        }
        
        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.75rem;
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            font-size: 0.75rem;
            color: var(--gray-500);
            font-weight: 500;
        }

        .orders-list {
            max-height: 420px;
            overflow-y: auto;
        }
        
        .order-item {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--gray-100);
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
        }
        
        .order-item:hover {
            background: var(--gray-50);
        }
        
        .order-status {
            padding: 0.25rem 0.625rem;
            border-radius: 2rem;
            font-size: 0.6875rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .order-status.pickup_scheduled { background: #fef3c7; color: #92400e; }
        .order-status.processing { background: #dbeafe; color: #1e40af; }
        .order-status.delivered { background: #d1fae5; color: #065f46; }
        .order-status.cancelled { background: #fee2e2; color: #991b1b; }

        .btn-primary {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.625rem 1.5rem;
            border-radius: 2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }
        
        .btn-primary:hover:not(:disabled) {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }
        
        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .btn-secondary {
            background: white;
            border: 1px solid var(--gray-300);
            color: var(--gray-700);
            padding: 0.625rem 1.5rem;
            border-radius: 2rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }
        
        .btn-secondary:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--gray-50);
        }
        
        .button-group {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }

        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.375rem;
            display: block;
            color: var(--gray-700);
        }
        
        .form-control, .form-select {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1px solid var(--gray-200);
            border-radius: 0.75rem;
            font-size: 0.875rem;
            transition: all 0.2s;
        }
        
        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(15, 59, 44, 0.1);
        }

        .payment-options {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .payment-option {
            flex: 1;
            min-width: 140px;
            border: 1px solid var(--gray-200);
            border-radius: 0.75rem;
            padding: 0.75rem;
            cursor: pointer;
            background: white;
            transition: all 0.2s;
        }
        
        .payment-option:hover {
            border-color: var(--primary);
            background: var(--gray-50);
        }
        
        .payment-option input[type="radio"] {
            margin-right: 0.5rem;
        }
        
        .payment-option:has(input:checked) {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .toast-notification {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: var(--gray-900);
            color: white;
            padding: 0.75rem 1.25rem;
            border-radius: 2rem;
            display: flex;
            align-items: center;
            gap: 0.625rem;
            z-index: 1000;
            animation: slideIn 0.3s ease;
            box-shadow: var(--shadow-lg);
            font-size: 0.875rem;
        }
        
        .toast-notification.error {
            background: var(--danger);
        }
        
        .toast-notification.success {
            background: var(--success);
        }
        
        .toast-notification.info {
            background: var(--primary);
        }
        
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.6s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .suggestions-dropdown {
            position: absolute;
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 0.75rem;
            width: calc(100% - 2rem);
            z-index: 1000;
            max-height: 200px;
            overflow-y: auto;
            box-shadow: var(--shadow-md);
        }
        
        .suggestions-dropdown div {
            padding: 0.625rem 1rem;
            cursor: pointer;
            font-size: 0.875rem;
            border-bottom: 1px solid var(--gray-100);
        }
        
        .suggestions-dropdown div:hover {
            background: var(--primary-light);
        }
        
        .location-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 0.5rem;
            font-size: 0.875rem;
            margin: 0.5rem 1rem;
        }
        
        .text-center { text-align: center; }
        .text-muted { color: var(--gray-500); }
        .py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
        .py-5 { padding-top: 2rem; padding-bottom: 2rem; }
        .mt-2 { margin-top: 0.5rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .cursor-pointer { cursor: pointer; }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="container">
        <a href="{{ route('client.dashboard') }}" class="navbar-brand">
            <i class="fas fa-tshirt"></i>
            <span>LaundryHub Pro</span>
        </a>
        
        <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
            <i class="fas fa-bars"></i>
        </button>
        
        <div class="navbar-links" id="navbarLinks">
            <a href="{{ route('client.dashboard') }}" class="nav-link active">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
            <a href="{{ route('client.orders.index') }}" class="nav-link">
                <i class="fas fa-shopping-bag"></i> My Orders
            </a>
            <a href="{{ route('client.locations.index') }}" class="nav-link">
                <i class="fas fa-map-marker-alt"></i> Locations
            </a>
            <a href="{{ route('client.wallet.index') }}" class="nav-link">
                <i class="fas fa-wallet"></i> Wallet
            </a>
       
                <i class="fas fa-question-circle"></i> Help
            </a>
        </div>
    </div>
</nav>

<div class="dashboard-header">
    <div class="container">
        <div class="header-wrapper">
            <div class="header-title">
                <h1>Welcome back, {{ auth()->user()->name }}! 🎓</h1>
                <p>Student laundry service - Clean clothes delivered to your doorstep</p>
            </div>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <div class="wallet-badge">
                    <i class="fas fa-wallet"></i>
                    <span>K{{ number_format(auth()->user()->wallet->balance ?? 0, 2) }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="student-info-banner">
        <div class="student-details">
            <div class="student-detail-item">
                <i class="fas fa-building"></i>
                <span><strong>Hostel:</strong> {{ auth()->user()->hostel_name ?? 'Not set' }}</span>
            </div>
            <div class="student-detail-item">
                <i class="fas fa-door-open"></i>
                <span><strong>Room/Block:</strong> {{ auth()->user()->room_number ?? 'Not set' }}</span>
            </div>
            <div class="student-detail-item">
                <i class="fas fa-phone"></i>
                <span><strong>Contact:</strong> {{ auth()->user()->phone ?? 'Not set' }}</span>
            </div>
            <div class="student-detail-item">
                <i class="fas fa-envelope"></i>
                <span><strong>Email:</strong> {{ auth()->user()->email }}</span>
            </div>
            <div class="student-detail-item">
                <i class="fas fa-graduation-cap"></i>
                <span><strong>Student ID:</strong> {{ auth()->user()->student_id ?? 'Not set' }}</span>
            </div>
        </div>
        <button class="edit-student-btn" onclick="openStudentModal()">
            <i class="fas fa-edit"></i> Update Details
        </button>
    </div>

    <div id="studentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-user-graduate"></i> Update Student Details</h3>
                <button class="close-modal" onclick="closeStudentModal()">&times;</button>
            </div>
            <form id="studentDetailsForm" method="POST" action="{{ route('client.update-student-details') }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">🏛️ Select Your Institution</label>
                        <select id="institution_select" class="form-control" onchange="fillInstitutionDetails()">
                            <option value="">-- Select your university/college --</option>
                            <optgroup label="Universities">
                                <option value="University of Zambia (UNZA)">🏫 University of Zambia (UNZA)</option>
                                <option value="Copperbelt University (CBU)">🏫 Copperbelt University (CBU)</option>
                                <option value="Mulungushi University">🏫 Mulungushi University</option>
                                <option value="Levy Mwanawasa Medical University">🏥 Levy Mwanawasa Medical University</option>
                                <option value="Chalo University">🏫 Chalo University</option>
                                <option value="Northrise University">🏫 Northrise University</option>
                                <option value="Rusangu University">🏫 Rusangu University</option>
                                <option value="Cavendish University">🏫 Cavendish University</option>
                            </optgroup>
                            <optgroup label="Colleges">
                                <option value="Evelyn Hone College">🏛️ Evelyn Hone College</option>
                                <option value="NIPA (National Institute of Public Administration)">🏛️ NIPA</option>
                                <option value="NRDC (Natural Resources Development College)">🏛️ NRDC</option>
                                <option value="ZAMIM (Zambia Institute of Management)">🏛️ ZAMIM</option>
                                <option value="LBTC (Lusaka Business and Technical College)">🏛️ LBTC</option>
                                <option value="KIT (Kitwe Institute of Technology)">🏛️ KIT</option>
                                <option value="NORTHEC (Northern Technical College)">🏛️ NORTHEC</option>
                            </optgroup>
                            <optgroup label="Student Hostels">
                                <option value="Great North Road Hostel">🏠 Great North Road Hostel</option>
                                <option value="Kabulonga Hostel">🏠 Kabulonga Hostel</option>
                                <option value="Libala Hostel">🏠 Libala Hostel</option>
                                <option value="Woodlands Hostel">🏠 Woodlands Hostel</option>
                                <option value="East Park Hostel">🏠 East Park Hostel</option>
                                <option value="Thornpark Hostel">🏠 Thornpark Hostel</option>
                                <option value="Roma Hostel">🏠 Roma Hostel</option>
                                <option value="Ibex Hill Hostel">🏠 Ibex Hill Hostel</option>
                                <option value="Avondale Hostel">🏠 Avondale Hostel</option>
                                <option value="Sunnyvale Hostel">🏠 Sunnyvale Hostel</option>
                            </optgroup>
                            <optgroup label="Other">
                                <option value="Other">📍 Other (Please specify)</option>
                            </optgroup>
                        </select>
                        <small class="text-muted" style="display: block; margin-top: 0.25rem;">
                            <i class="fas fa-info-circle"></i> Selecting your institution helps us auto-detect your location
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Hostel/Residence Name</label>
                        <input type="text" name="hostel_name" id="hostel_name" class="form-control" value="{{ auth()->user()->hostel_name ?? '' }}" placeholder="e.g., Great North Road Hall, Kabulonga Hostel">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Room/Block Number</label>
                        <input type="text" name="room_number" id="room_number" class="form-control" value="{{ auth()->user()->room_number ?? '' }}" placeholder="e.g., Block A, Room 204">
                        <small class="text-muted">Include block name and room number for easy delivery</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone" class="form-control" value="{{ auth()->user()->phone ?? '' }}" placeholder="e.g., 0977123456">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Student ID Number</label>
                        <input type="text" name="student_id" class="form-control" value="{{ auth()->user()->student_id ?? '' }}" placeholder="e.g., 2024123456">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Alternative Contact (Optional)</label>
                        <input type="tel" name="alternative_phone" class="form-control" value="{{ auth()->user()->alternative_phone ?? '' }}" placeholder="Emergency contact">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">📍 Location Preview</label>
                        <div id="location-preview" style="height: 150px; background: #eef2ff; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; flex-direction: column; gap: 0.5rem;">
                            <i class="fas fa-map-marker-alt" style="color: var(--primary); font-size: 2rem;"></i>
                            <span style="color: var(--gray-500); font-size: 0.75rem;">Select institution to see location</span>
                        </div>
                        <p class="text-muted" style="font-size: 0.7rem; margin-top: 0.5rem;">
                            <i class="fas fa-info-circle"></i> This helps our drivers find your exact location
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeStudentModal()">Cancel</button>
                    <button type="submit" class="btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <div class="referral-banner">
        <div class="referral-info">
            <i class="fas fa-gift" style="font-size: 1.5rem; color: var(--warning);"></i>
            <div>
                <strong>Student Referral Program!</strong>
                <p style="margin: 0; font-size: 0.8125rem;">Refer a friend and both get K20 off your next order! 🎉</p>
            </div>
        </div>
        <div class="referral-code-box">
            <span class="referral-code">{{ auth()->user()->referral_code }}</span>
            <button class="copy-btn" onclick="copyReferralCode()">
                <i class="fas fa-copy"></i>
            </button>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="form-card">
            <div class="card-header">
                <h4>
                    <i class="fas fa-plus-circle" style="color: var(--primary);"></i>
                    Schedule Pickup
                </h4>
            </div>
            <div class="card-body">
                <form id="orderForm" action="{{ route('client.orders.store') }}" method="POST">
                    @csrf
                    
                    <div class="step-wrapper">
                        <div class="step-indicator">
                            <div class="step active" onclick="showStep(1)">
                                <div class="step-number">1</div>
                                <div class="step-label">Location</div>
                            </div>
                            <div class="step" onclick="showStep(2)">
                                <div class="step-number">2</div>
                                <div class="step-label">Basket</div>
                            </div>
                            <div class="step" onclick="showStep(3)">
                                <div class="step-number">3</div>
                                <div class="step-label">Extras</div>
                            </div>
                            <div class="step" onclick="showStep(4)">
                                <div class="step-number">4</div>
                                <div class="step-label">Payment</div>
                            </div>
                        </div>
                    </div>

                    <div id="step1" class="form-step">
                        <div class="room-details-card">
                            <i class="fas fa-home"></i>
                            <div>
                                <strong>Your Location:</strong> 
                                @if(auth()->user()->hostel_name && auth()->user()->room_number)
                                    {{ auth()->user()->hostel_name }}, Room {{ auth()->user()->room_number }}
                                @else
                                    Please update your student details above
                                @endif
                            </div>
                        </div>
                        
                        <div class="auto-locate-badge" onclick="autoLocateFromStudentDetails()">
                            <i class="fas fa-location-dot"></i>
                            <span>📍 Auto-detect my location from student details</span>
                            <i class="fas fa-chevron-right" style="margin-left: auto;"></i>
                        </div>
                        
                        <div class="map-section">
                            <div class="map-card">
                                <h6><i class="fas fa-map-marker-alt" style="color: var(--primary);"></i> Pickup Location</h6>
                                <div id="pickup-map" class="map-container"></div>
                                <div class="search-box">
                                    <i class="fas fa-search"></i>
                                    <input type="text" id="pickup-search" placeholder="Search address...">
                                </div>
                                
                                <div class="location-select-wrapper">
                                    <select id="pickup-select" class="location-select" onchange="selectPickupFromDropdown(this.value)">
                                        <option value="">-- Select a saved location --</option>
                                        @php
                                            $userLocations = auth()->user()->locations;
                                            $usedLocationIds = \App\Models\Order::where('client_id', auth()->id())
                                                ->where(function($q) {
                                                    $q->whereNotNull('pickup_location_id')
                                                      ->orWhereNotNull('delivery_location_id');
                                                })
                                                ->get()
                                                ->pluck('pickup_location_id')
                                                ->merge(\App\Models\Order::where('client_id', auth()->id())->pluck('delivery_location_id'))
                                                ->unique()
                                                ->toArray();
                                        @endphp
                                        
                                        @if($userLocations->count() > 0)
                                            <optgroup label="Your Locations">
                                                @foreach($userLocations as $location)
                                                    @php
                                                        $isUsed = in_array($location->id, $usedLocationIds);
                                                    @endphp
                                                    <option value="{{ $location->id }}" 
                                                            data-lat="{{ $location->latitude }}" 
                                                            data-lng="{{ $location->longitude }}"
                                                            data-name="{{ addslashes($location->name) }}"
                                                            data-address="{{ addslashes($location->address) }}">
                                                        {{ $location->name }} - {{ Str::limit($location->address, 40) }}
                                                        @if($isUsed) (Used in orders) @endif
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    </select>
                                    <a href="{{ route('client.locations.index') }}" class="manage-locations-link">
                                        <i class="fas fa-edit"></i> Manage Saved Locations
                                    </a>
                                </div>
                                
                                <div id="pickup-location-display"></div>
                                <input type="hidden" name="pickup_location_id" id="pickup_location_id" value="">
                                <input type="hidden" name="pickup_latitude" id="pickup_latitude" value="">
                                <input type="hidden" name="pickup_longitude" id="pickup_longitude" value="">
                                <input type="hidden" name="pickup_address" id="pickup_address" value="">
                                <input type="hidden" name="pickup_name" id="pickup_name" value="">
                                <input type="hidden" name="pickup_phone" id="pickup_phone" value="{{ auth()->user()->phone ?? '' }}">
                            </div>

                            <div class="map-card">
                                <h6><i class="fas fa-flag-checkered" style="color: var(--success);"></i> Delivery Location</h6>
                                <div id="delivery-map" class="map-container"></div>
                                <div class="search-box">
                                    <i class="fas fa-search"></i>
                                    <input type="text" id="delivery-search" placeholder="Search address...">
                                </div>
                                
                                <div class="location-select-wrapper">
                                    <select id="delivery-select" class="location-select" onchange="selectDeliveryFromDropdown(this.value)">
                                        <option value="">-- Select a saved location --</option>
                                        @if($userLocations->count() > 0)
                                            <optgroup label="Your Locations">
                                                @foreach($userLocations as $location)
                                                    @php
                                                        $isUsed = in_array($location->id, $usedLocationIds);
                                                    @endphp
                                                    <option value="{{ $location->id }}" 
                                                            data-lat="{{ $location->latitude }}" 
                                                            data-lng="{{ $location->longitude }}"
                                                            data-name="{{ addslashes($location->name) }}"
                                                            data-address="{{ addslashes($location->address) }}">
                                                        {{ $location->name }} - {{ Str::limit($location->address, 40) }}
                                                        @if($isUsed) (Used in orders) @endif
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    </select>
                                    <a href="{{ route('client.locations.index') }}" class="manage-locations-link">
                                        <i class="fas fa-edit"></i> Manage Saved Locations
                                    </a>
                                </div>
                                
                                <div id="delivery-location-display"></div>
                                <input type="hidden" name="delivery_location_id" id="delivery_location_id" value="">
                                <input type="hidden" name="delivery_latitude" id="delivery_latitude" value="">
                                <input type="hidden" name="delivery_longitude" id="delivery_longitude" value="">
                                <input type="hidden" name="delivery_address" id="delivery_address" value="">
                                <input type="hidden" name="delivery_name" id="delivery_name" value="">
                                <input type="hidden" name="delivery_phone" id="delivery_phone" value="{{ auth()->user()->phone ?? '' }}">
                            </div>
                        </div>
                        
                        <div class="button-group">
                            <div></div>
                            <button type="button" class="btn-primary" onclick="validateAndNext()">
                                Continue <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <div id="step2" class="form-step" style="display: none;">
                        <div class="basket-grid">
                            @foreach($baskets as $basket)
                                <div class="basket-card" onclick="selectBasket({{ $basket->id }}, {{ $basket->base_price }})"
                                     data-basket-id="{{ $basket->id }}">
                                    <div class="basket-icon">
                                        <i class="fas fa-{{ $basket->size === 'small' ? 'box' : ($basket->size === 'medium' ? 'box-open' : 'boxes') }}"></i>
                                    </div>
                                    <div class="basket-name"><strong>{{ $basket->name }}</strong></div>
                                    <div class="basket-capacity" style="font-size: 0.75rem; color: var(--gray-500);">{{ $basket->capacity_kg }} kg capacity</div>
                                    <div class="basket-price">K{{ number_format($basket->base_price, 2) }}</div>
                                    <span style="font-size: 0.6875rem; background: var(--gray-100); padding: 0.25rem 0.625rem; border-radius: 1rem;">{{ ucfirst($basket->size) }}</span>
                                    @if(auth()->user()->student_id)
                                        <div class="student-discount-badge">
                                            <i class="fas fa-graduation-cap"></i> 10% Student Discount
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="basket_id" id="basket_id" value="">
                        <input type="hidden" name="basket_price" id="basket_price" value="">
                        
                        <div class="button-group">
                            <button type="button" class="btn-secondary" onclick="prevStep(1)">
                                <i class="fas fa-arrow-left"></i> Back
                            </button>
                            <button type="button" class="btn-primary" onclick="validateBasketAndNext()">
                                Continue <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <div id="step3" class="form-step" style="display: none;">
                        @foreach($specialItems->groupBy('category') as $category => $items)
                            <div style="margin-bottom: 1.25rem;">
                                <h6 style="font-weight: 600; margin-bottom: 0.75rem; font-size: 0.875rem; color: var(--gray-700);">{{ ucfirst($category) }}</h6>
                                <div class="items-grid">
                                    @foreach($items as $item)
                                        <div class="item-card" onclick="toggleSpecialItem({{ $item->id }}, {{ $item->surcharge }})">
                                            <div class="item-checkbox" id="checkbox-{{ $item->id }}">
                                                <i class="fas fa-check" style="font-size: 0.625rem; display: none;"></i>
                                            </div>
                                            <div style="flex: 1;">
                                                <strong style="font-size: 0.875rem;">{{ $item->name }}</strong>
                                                <div style="font-size: 0.75rem; color: var(--success); font-weight: 500;">+K{{ number_format($item->surcharge, 2) }}</div>
                                            </div>
                                            <input type="checkbox" name="special_items[]" value="{{ $item->id }}" 
                                                   id="item-{{ $item->id }}" class="special-item" 
                                                   data-surcharge="{{ $item->surcharge }}" style="display: none;">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                        
                        <div class="button-group">
                            <button type="button" class="btn-secondary" onclick="prevStep(2)">
                                <i class="fas fa-arrow-left"></i> Back
                            </button>
                            <button type="button" class="btn-primary" onclick="nextStep(4)">
                                Continue <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <div id="step4" class="form-step" style="display: none;">
                        <div class="price-summary" id="price-summary" style="display: none;">
                            <div class="price-row">
                                <span>Subtotal</span>
                                <span>K <span id="subtotal">0.00</span></span>
                            </div>
                            <div class="price-row">
                                <span>Surcharges</span>
                                <span>+ K <span id="surcharges">0.00</span></span>
                            </div>
                            @if(auth()->user()->student_id)
                                <div class="price-row">
                                    <span><i class="fas fa-graduation-cap"></i> Student Discount (10%)</span>
                                    <span>- K <span id="studentDiscount">0.00</span></span>
                                </div>
                            @endif
                            <div class="price-total">
                                <span>Total</span>
                                <span>K <span id="total">0.00</span></span>
                            </div>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <div class="form-group">
                                <label class="form-label">Pickup Date & Time</label>
                                <input type="datetime-local" name="pickup_scheduled_at" class="form-control" 
                                       min="{{ now()->format('Y-m-d\TH:i') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Payment Method</label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="">Select payment method</option>
                                    <option value="mtn">📱 MTN Mobile Money</option>
                                    <option value="airtel">📱 Airtel Money</option>
                                    <option value="zamtel">📱 Zamtel Money</option>
                                    <option value="wallet">💰 Wallet (K{{ number_format(auth()->user()->wallet->balance ?? 0, 2) }})</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Payment Type</label>
                            <div class="payment-options">
                                <label class="payment-option">
                                    <input type="radio" name="payment_type" value="prepaid" checked> 
                                    <span><strong>Prepaid</strong><br><small style="color: var(--success);">5% discount</small></span>
                                </label>
                                <label class="payment-option">
                                    <input type="radio" name="payment_type" value="postpaid"> 
                                    <span><strong>Pay After</strong><br><small>On delivery</small></span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Special Instructions</label>
                            <textarea name="special_instructions" class="form-control" rows="3" 
                                      placeholder="Gate code, landmark, room number, or any special instructions for the delivery person..."></textarea>
                        </div>
                        
                        <div class="button-group">
                            <button type="button" class="btn-secondary" onclick="prevStep(3)">
                                <i class="fas fa-arrow-left"></i> Back
                            </button>
                            <button type="submit" class="btn-primary" id="placeOrderBtn">
                                <i class="fas fa-check"></i> Place Order
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #e8f3ef; color: var(--primary);">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="stat-value">{{ $stats['total_orders'] ?? 0 }}</div>
                    <div class="stat-label">Total Orders</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #d1fae5; color: var(--success);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-value">{{ $stats['completed_orders'] ?? 0 }}</div>
                    <div class="stat-label">Completed</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #fee2e2; color: var(--danger);">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="stat-value">K{{ number_format($stats['total_spent'] ?? 0, 2) }}</div>
                    <div class="stat-label">Total Spent</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #fffbeb; color: var(--warning);">
                        <i class="fas fa-gift"></i>
                    </div>
                    <div class="stat-value">K{{ number_format($stats['referral_earnings'] ?? 0, 2) }}</div>
                    <div class="stat-label">Referral Earnings</div>
                </div>
            </div>

            <div class="form-card">
                <div class="card-header">
                    <h5><i class="fas fa-history"></i> Recent Orders</h5>
                </div>
                <div class="orders-list">
                    @forelse($recentOrders as $order)
                        <a href="{{ route('client.orders.show', $order) }}" class="order-item">
                            <div>
                                <strong style="font-size: 0.875rem;">Order #{{ $order->order_number }}</strong>
                                <div style="font-size: 0.75rem; color: var(--gray-500); margin-top: 0.25rem;">
                                    {{ $order->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <div class="order-status {{ $order->status }}">{{ str_replace('_', ' ', $order->status) }}</div>
                                <div style="font-weight: 600; margin-top: 0.375rem; font-size: 0.875rem;">K{{ number_format($order->total, 2) }}</div>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-inbox" style="font-size: 3rem; color: var(--gray-300); margin-bottom: 1rem;"></i>
                            <p class="text-muted" style="font-size: 0.875rem;">No orders yet</p>
                            <button class="btn-primary" onclick="showStep(1)" style="padding: 0.5rem 1rem; font-size: 0.8125rem;">Schedule Your First Pickup</button>
                        </div>
                    @endforelse
                </div>
                @if($recentOrders->isNotEmpty())
                    <div style="padding: 1rem; text-align: center; border-top: 1px solid var(--gray-200);">
                        <a href="{{ route('client.orders.index') }}" class="btn-secondary" style="text-decoration: none;">View All Orders</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Global variables
let pickupMap, deliveryMap;
let pickupMarker = null, deliveryMarker = null;
let selectedBasketId = null, basketPrice = 0;
let currentStep = 1;
let selectedSpecialSurcharges = 0;
let isStudent = {{ auth()->user()->student_id ? 'true' : 'false' }};

// Store all locations
let allLocations = @json(auth()->user()->locations->toArray());

// ACCURATE university and hostel locations with exact coordinates
const campusLocations = {
    // NIPA - Exact coordinates (Great East Road, Lusaka)
    'nipa': { lat: -15.4100, lng: 28.3150, name: 'NIPA - National Institute of Public Administration', address: 'Great East Road, Lusaka' },
    'national institute of public administration': { lat: -15.4100, lng: 28.3150, name: 'NIPA', address: 'Great East Road, Lusaka' },
    
    // Universities
    'university of zambia': { lat: -15.3925, lng: 28.3328, name: 'UNZA', address: 'Great East Road, Lusaka' },
    'unza': { lat: -15.3925, lng: 28.3328, name: 'UNZA', address: 'Great East Road, Lusaka' },
    'copperbelt university': { lat: -12.7969, lng: 28.2325, name: 'CBU', address: 'Jambo Drive, Kitwe' },
    'cbu': { lat: -12.7969, lng: 28.2325, name: 'CBU', address: 'Jambo Drive, Kitwe' },
    'mulungushi university': { lat: -14.4469, lng: 28.2964, name: 'Mulungushi University', address: 'Great North Road, Kabwe' },
    'levy mwanawasa medical university': { lat: -15.4040, lng: 28.2730, name: 'Levy Mwanawasa Medical University', address: 'Independence Avenue, Lusaka' },
    'chalo university': { lat: -15.4160, lng: 28.2830, name: 'Chalo University', address: 'Chachacha Road, Lusaka' },
    'cavendish university': { lat: -15.4169, lng: 28.2886, name: 'Cavendish University', address: 'Great East Road, Lusaka' },
    
    // Colleges
    'evelyn hone college': { lat: -15.4200, lng: 28.3000, name: 'Evelyn Hone College', address: 'Independence Avenue, Lusaka' },
    'evelyn hone': { lat: -15.4200, lng: 28.3000, name: 'Evelyn Hone College', address: 'Independence Avenue, Lusaka' },
    'nrdc': { lat: -15.4220, lng: 28.3180, name: 'NRDC', address: 'Great East Road, Lusaka' },
    'zamim': { lat: -15.4250, lng: 28.3100, name: 'ZAMIM', address: 'New Kasama Road, Lusaka' },
    'lbtc': { lat: -15.4180, lng: 28.3050, name: 'LBTC', address: 'Lusaka' },
    
    // Hostels
    'great north road': { lat: -15.3925, lng: 28.3328, name: 'Great North Road Hostels', address: 'Great North Road, Lusaka' },
    'kabulonga': { lat: -15.4205, lng: 28.3315, name: 'Kabulonga Hostel', address: 'Kabulonga, Lusaka' },
    'libala': { lat: -15.4050, lng: 28.2950, name: 'Libala Hostel', address: 'Libala, Lusaka' },
    'woodlands': { lat: -15.3800, lng: 28.3200, name: 'Woodlands Hostel', address: 'Woodlands, Lusaka' },
    'roma': { lat: -15.4300, lng: 28.3250, name: 'Roma Hostel', address: 'Roma, Lusaka' },
    'ibex hill': { lat: -15.3750, lng: 28.3400, name: 'Ibex Hill Hostel', address: 'Ibex Hill, Lusaka' }
};

// Fill institution details function
function fillInstitutionDetails() {
    const select = document.getElementById('institution_select');
    const selectedValue = select.value;
    const hostelInput = document.getElementById('hostel_name');
    const previewDiv = document.getElementById('location-preview');
    
    if (selectedValue && selectedValue !== 'Other') {
        hostelInput.value = selectedValue;
        
        // Try to find matching location
        const searchTerm = selectedValue.toLowerCase();
        let matchedLocation = null;
        
        for (const [key, location] of Object.entries(campusLocations)) {
            if (searchTerm.includes(key) || key.includes(searchTerm)) {
                matchedLocation = location;
                break;
            }
        }
        
        if (matchedLocation) {
            previewDiv.innerHTML = `
                <div style="text-align: center;">
                    <i class="fas fa-check-circle" style="color: var(--success); font-size: 1.5rem;"></i>
                    <p style="margin-top: 0.5rem; font-size: 0.75rem; color: var(--gray-600);">
                        📍 ${matchedLocation.name}<br>
                        ${matchedLocation.address}<br>
                        <small>Lat: ${matchedLocation.lat}, Lng: ${matchedLocation.lng}</small>
                    </p>
                    <button type="button" class="btn-secondary" style="padding: 0.25rem 0.75rem; font-size: 0.7rem; margin-top: 0.5rem;" onclick="previewLocationOnMap(${matchedLocation.lat}, ${matchedLocation.lng}, '${matchedLocation.name}')">
                        <i class="fas fa-map"></i> Preview on Map
                    </button>
                </div>
            `;
        } else {
            previewDiv.innerHTML = `
                <div style="text-align: center;">
                    <i class="fas fa-map-marker-alt" style="color: var(--warning); font-size: 1.5rem;"></i>
                    <p style="margin-top: 0.5rem; font-size: 0.75rem; color: var(--gray-600);">
                        Location will be auto-detected when you save
                    </p>
                </div>
            `;
        }
    } else if (selectedValue === 'Other') {
        hostelInput.value = '';
        previewDiv.innerHTML = `
            <div style="text-align: center;">
                <i class="fas fa-edit" style="color: var(--primary); font-size: 1.5rem;"></i>
                <p style="margin-top: 0.5rem; font-size: 0.75rem; color: var(--gray-600);">
                    Please enter your hostel/residence name
                </p>
            </div>
        `;
    }
}

// Preview location on map
function previewLocationOnMap(lat, lng, name) {
    closeStudentModal();
    setTimeout(() => {
        const fullAddress = `${name}, Lusaka, Zambia`;
        setMapLocation('pickup', lat, lng, fullAddress);
        setMapLocation('delivery', lat, lng, fullAddress);
        showToast(`Location set to ${name}`, 'success');
    }, 500);
}

// Set map location
function setMapLocation(type, lat, lng, address) {
    const latlng = [parseFloat(lat), parseFloat(lng)];
    const map = type === 'pickup' ? pickupMap : deliveryMap;
    const marker = type === 'pickup' ? pickupMarker : deliveryMarker;
    
    map.setView(latlng, 17);
    
    if (marker) {
        map.removeLayer(marker);
    }
    
    const newMarker = L.marker(latlng).addTo(map);
    newMarker.bindPopup(address).openPopup();
    
    const displayDiv = document.getElementById(type + '-location-display');
    const locationName = address.split(',')[0];
    
    if (type === 'pickup') {
        pickupMarker = newMarker;
        document.getElementById('pickup_latitude').value = lat;
        document.getElementById('pickup_longitude').value = lng;
        document.getElementById('pickup_address').value = address;
        document.getElementById('pickup_name').value = locationName;
        displayDiv.innerHTML = '<div class="location-badge"><i class="fas fa-check-circle"></i> Pickup: ' + locationName + '</div>';
    } else {
        deliveryMarker = newMarker;
        document.getElementById('delivery_latitude').value = lat;
        document.getElementById('delivery_longitude').value = lng;
        document.getElementById('delivery_address').value = address;
        document.getElementById('delivery_name').value = locationName;
        displayDiv.innerHTML = '<div class="location-badge"><i class="fas fa-check-circle"></i> Delivery: ' + locationName + '</div>';
    }
    
    // Update search box
    document.getElementById(type + '-search').value = locationName;
}

// Mobile menu toggle
function toggleMobileMenu() {
    const navbarLinks = document.getElementById('navbarLinks');
    navbarLinks.classList.toggle('active');
}

// Student Modal Functions
function openStudentModal() {
    document.getElementById('studentModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeStudentModal() {
    document.getElementById('studentModal').classList.remove('active');
    document.body.style.overflow = '';
}

// Auto-locate based on student details - FIXED for NIPA
async function autoLocateFromStudentDetails() {
    const hostelNameElem = document.getElementById('hostel_name');
    const roomNumberElem = document.getElementById('room_number');
    
    const hostelName = (hostelNameElem?.value || "{{ auth()->user()->hostel_name ?? '' }}").toLowerCase();
    const roomNumber = roomNumberElem?.value || "{{ auth()->user()->room_number ?? '' }}";
    
    if (!hostelName) {
        showToast('Please update your hostel/residence details first', 'error');
        openStudentModal();
        return;
    }
    
    showToast('Searching for your location...', 'info');
    
    // Check for NIPA specifically
    let matchedLocation = null;
    
    if (hostelName.includes('nipa')) {
        matchedLocation = { lat: -15.4100, lng: 28.3150, name: 'NIPA', address: 'Great East Road, Lusaka' };
    } else if (hostelName.includes('unza') || hostelName.includes('university of zambia')) {
        matchedLocation = { lat: -15.3925, lng: 28.3328, name: 'UNZA', address: 'Great East Road, Lusaka' };
    } else if (hostelName.includes('cbu') || hostelName.includes('copperbelt')) {
        matchedLocation = { lat: -12.7969, lng: 28.2325, name: 'CBU', address: 'Jambo Drive, Kitwe' };
    } else if (hostelName.includes('evelyn hone')) {
        matchedLocation = { lat: -15.4200, lng: 28.3000, name: 'Evelyn Hone College', address: 'Independence Avenue, Lusaka' };
    } else {
        // Try to find in campusLocations
        for (const [key, coords] of Object.entries(campusLocations)) {
            if (hostelName.includes(key)) {
                matchedLocation = coords;
                break;
            }
        }
    }
    
    if (matchedLocation) {
        const fullAddress = `${matchedLocation.name}${roomNumber ? ', Room ' + roomNumber : ''}, ${matchedLocation.address}`;
        setMapLocation('pickup', matchedLocation.lat, matchedLocation.lng, fullAddress);
        setTimeout(() => {
            setMapLocation('delivery', matchedLocation.lat, matchedLocation.lng, fullAddress);
        }, 500);
        showToast(`Location set to ${matchedLocation.name}`, 'success');
    } else {
        // Fallback to Lusaka city center
        const fallbackLat = -15.3875;
        const fallbackLng = 28.3228;
        const fullAddress = `${hostelName}${roomNumber ? ', Room ' + roomNumber : ''}, Lusaka, Zambia`;
        setMapLocation('pickup', fallbackLat, fallbackLng, fullAddress);
        setMapLocation('delivery', fallbackLat, fallbackLng, fullAddress);
        showToast('Using Lusaka city center. You can adjust the marker.', 'warning');
    }
}

// Handle student details form submission
document.getElementById('studentDetailsForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="loading-spinner"></span> Saving...';
    
    try {
        const response = await fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast('Student details updated successfully!', 'success');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            showToast(data.message || 'Error updating details', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Network error. Please try again.', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    initializeMaps();
    
    var paymentRadios = document.querySelectorAll('input[name="payment_type"]');
    paymentRadios.forEach(function(radio) {
        radio.addEventListener('change', function() {
            if (currentStep === 4) calculatePrice();
        });
    });
    
    setupFormSubmission();
    
    // Auto-locate after maps are initialized
    setTimeout(function() {
        const hostelName = "{{ auth()->user()->hostel_name ?? '' }}";
        if (hostelName) {
            autoLocateFromStudentDetails();
        }
    }, 1000);
});

function selectPickupFromDropdown(locationId) {
    if (!locationId) return;
    var select = document.getElementById('pickup-select');
    var option = select.querySelector('option[value="' + locationId + '"]');
    var lat = option.getAttribute('data-lat');
    var lng = option.getAttribute('data-lng');
    var name = option.getAttribute('data-name');
    var address = option.getAttribute('data-address');
    setMapLocation('pickup', lat, lng, address);
}

function selectDeliveryFromDropdown(locationId) {
    if (!locationId) return;
    var select = document.getElementById('delivery-select');
    var option = select.querySelector('option[value="' + locationId + '"]');
    var lat = option.getAttribute('data-lat');
    var lng = option.getAttribute('data-lng');
    var name = option.getAttribute('data-name');
    var address = option.getAttribute('data-address');
    setMapLocation('delivery', lat, lng, address);
}

function setupFormSubmission() {
    var form = document.getElementById('orderForm');
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        var pickupLat = document.getElementById('pickup_latitude').value;
        var deliveryLat = document.getElementById('delivery_latitude').value;
        
        if (!pickupLat || pickupLat === '') {
            showToast('Please select a pickup location', 'error');
            showStep(1);
            return;
        }
        
        if (!deliveryLat || deliveryLat === '') {
            showToast('Please select a delivery location', 'error');
            showStep(1);
            return;
        }
        
        if (!selectedBasketId) {
            showToast('Please select a basket size', 'error');
            showStep(2);
            return;
        }
        
        var submitBtn = document.getElementById('placeOrderBtn');
        var originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="loading-spinner"></span> Processing...';
        
        var formData = new FormData(form);
        
        try {
            var response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            var data = await response.json();
            
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(function() {
                    window.location.href = data.redirect_url;
                }, 1500);
            } else {
                showToast(data.message, 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Network error. Please try again.', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });
}

function initializeMaps() {
    var defaultCenter = [-15.3875, 28.3228];
    
    pickupMap = L.map('pickup-map').setView(defaultCenter, 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(pickupMap);
    
    deliveryMap = L.map('delivery-map').setView(defaultCenter, 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(deliveryMap);
    
    pickupMap.on('click', function(e) {
        reverseGeocode(e.latlng, 'pickup');
    });
    
    deliveryMap.on('click', function(e) {
        reverseGeocode(e.latlng, 'delivery');
    });
}

function reverseGeocode(latlng, type) {
    var url = 'https://nominatim.openstreetmap.org/reverse?format=json&lat=' + latlng.lat + '&lon=' + latlng.lng + '&zoom=18';
    
    fetch(url, {
        headers: {
            'Accept-Language': 'en',
            'User-Agent': 'LaundryHubPro/1.0'
        }
    })
    .then(function(response) { return response.json(); })
    .then(function(data) {
        if (data.display_name) {
            setMapLocation(type, latlng.lat, latlng.lng, data.display_name);
        }
    })
    .catch(function(error) { console.error('Reverse geocoding error:', error); });
}

function selectBasket(id, price) {
    selectedBasketId = id;
    basketPrice = price;
    document.getElementById('basket_id').value = id;
    document.getElementById('basket_price').value = price;
    
    document.querySelectorAll('.basket-card').forEach(function(card) {
        card.classList.remove('selected');
        if (card.getAttribute('data-basket-id') == id) {
            card.classList.add('selected');
        }
    });
    
    if (currentStep === 4) calculatePrice();
}

function toggleSpecialItem(itemId, surcharge) {
    var checkbox = document.getElementById('item-' + itemId);
    var card = checkbox.closest('.item-card');
    var checkIcon = card.querySelector('.item-checkbox i');
    
    checkbox.checked = !checkbox.checked;
    
    if (checkbox.checked) {
        card.classList.add('selected');
        if (checkIcon) checkIcon.style.display = 'block';
        selectedSpecialSurcharges += surcharge;
    } else {
        card.classList.remove('selected');
        if (checkIcon) checkIcon.style.display = 'none';
        selectedSpecialSurcharges -= surcharge;
    }
    
    if (currentStep === 4) calculatePrice();
}

function calculatePrice() {
    if (!selectedBasketId) return;
    
    var total = basketPrice + selectedSpecialSurcharges;
    var paymentType = document.querySelector('input[name="payment_type"]:checked');
    var studentDiscount = 0;
    
    if (isStudent) {
        studentDiscount = total * 0.10;
        total = total - studentDiscount;
    }
    
    if (paymentType && paymentType.value === 'prepaid') {
        total = total * 0.95;
    }
    
    document.getElementById('subtotal').textContent = (basketPrice + selectedSpecialSurcharges).toFixed(2);
    document.getElementById('surcharges').textContent = selectedSpecialSurcharges.toFixed(2);
    
    if (isStudent) {
        document.getElementById('studentDiscount').textContent = studentDiscount.toFixed(2);
    }
    
    document.getElementById('total').textContent = total.toFixed(2);
    document.getElementById('price-summary').style.display = 'block';
}

function showStep(step) {
    for (var i = 1; i <= 4; i++) {
        var stepDiv = document.getElementById('step' + i);
        if (stepDiv) stepDiv.style.display = 'none';
        var steps = document.querySelectorAll('.step');
        if (steps[i-1]) {
            steps[i-1].classList.remove('active', 'completed');
        }
    }
    
    document.getElementById('step' + step).style.display = 'block';
    
    for (var i = 1; i < step; i++) {
        var steps = document.querySelectorAll('.step');
        if (steps[i-1]) steps[i-1].classList.add('completed');
    }
    
    var steps = document.querySelectorAll('.step');
    if (steps[step-1]) steps[step-1].classList.add('active');
    
    currentStep = step;
    
    if (step === 1) {
        setTimeout(function() {
            if (pickupMap) pickupMap.invalidateSize();
            if (deliveryMap) deliveryMap.invalidateSize();
        }, 100);
    }
    
    if (step === 4) calculatePrice();
}

function validateAndNext() {
    var pickupLat = document.getElementById('pickup_latitude').value;
    var deliveryLat = document.getElementById('delivery_latitude').value;
    
    if (!pickupLat || pickupLat === '') {
        showToast('Please select a pickup location', 'error');
        return;
    }
    if (!deliveryLat || deliveryLat === '') {
        showToast('Please select a delivery location', 'error');
        return;
    }
    nextStep(2);
}

function validateBasketAndNext() {
    if (!selectedBasketId) {
        showToast('Please select a basket size', 'error');
        return;
    }
    nextStep(3);
}

function nextStep(step) {
    showStep(step);
}

function prevStep(step) {
    showStep(step);
}

function copyReferralCode() {
    var code = document.querySelector('.referral-code').textContent;
    navigator.clipboard.writeText(code).then(function() {
        showToast('Referral code copied to clipboard!', 'success');
    });
}

function showToast(message, type) {
    var existingToast = document.querySelector('.toast-notification');
    if (existingToast) existingToast.remove();
    
    var toast = document.createElement('div');
    toast.className = 'toast-notification ' + (type || 'info');
    var icon = type === 'success' ? 'fa-check-circle' : (type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle');
    toast.innerHTML = '<i class="fas ' + icon + '"></i> ' + message;
    document.body.appendChild(toast);
    
    setTimeout(function() {
        toast.remove();
    }, 3000);
}
</script>
</body>
</html>
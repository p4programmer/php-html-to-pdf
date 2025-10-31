<?php
declare(strict_types=1);

// Autoload Composer dependencies (Dompdf)
$autoloadPath = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=UTF-8');
    echo "Missing dependencies.\n\n";
    echo "To set up, run:\n";
    echo "  1) Install Composer: https://getcomposer.org/download/\n";
    echo "  2) In this project directory, run:\n";
    echo "     composer require dompdf/dompdf:^2.0\n";
    exit(1);
}

require $autoloadPath;

use Dompdf\Dompdf;
use Dompdf\Options;

// Pizza restaurant menu HTML template
$menuHtml = (function (): string {
    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Our Menu - Food & Drinks</title>
  <style>
    @page { margin: 0; }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { 
      font-family: DejaVu Sans, Arial, Helvetica, sans-serif; 
      color: #222; 
      background: #fff;
    }
    
    /* Dark Header Section */
    .header-dark {
      background: linear-gradient(to bottom, #2a2a2a 0%, #1a1a1a 100%);
      background-color: #1f1f1f;
      padding: 60px 40px 50px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }
    
    .header-dark::before {
      content: '';
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      background-image: 
        radial-gradient(circle at 20% 30%, rgba(255,255,255,0.05) 1px, transparent 1px),
        radial-gradient(circle at 80% 70%, rgba(255,255,255,0.03) 1px, transparent 1px);
      background-size: 50px 50px, 30px 30px;
    }
    
    .menu-title {
      font-size: 48px;
      font-family: DejaVu Serif, Times, serif;
      color: #6b8e23;
      font-style: italic;
      margin-bottom: 15px;
      position: relative;
      z-index: 1;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }
    
    .food-drinks {
      font-size: 18px;
      font-weight: bold;
      color: #ffffff;
      letter-spacing: 2px;
      margin-bottom: 8px;
      position: relative;
      z-index: 1;
    }
    
    .food-drinks::after {
      content: '';
      display: block;
      width: 100px;
      height: 3px;
      background: #6b8e23;
      margin: 8px auto 0;
    }
    
    .header-decoration {
      position: absolute;
      bottom: 20px;
      right: 40px;
      font-size: 14px;
      color: rgba(255,255,255,0.1);
      z-index: 0;
    }
    
    /* Main Content - White Background */
    .main-content {
      background: #ffffff;
      padding: 30px 40px 40px;
    }
    
    /* Paulo's Super Special Section */
    .special-section {
      margin-bottom: 35px;
    }
    
    .special-title {
      font-size: 20px;
      font-weight: bold;
      color: #6b8e23;
      margin-bottom: 15px;
      position: relative;
      padding-left: 15px;
    }
    
    .special-title::before {
      content: '';
      position: absolute;
      left: 0;
      top: 50%;
      transform: translateY(-50%);
      width: 5px;
      height: 25px;
      background: #6b8e23;
    }
    
    .price-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 10px;
    }
    
    .price-table thead {
      background: #f5f5f5;
    }
    
    .price-table th {
      padding: 10px;
      text-align: center;
      font-weight: bold;
      color: #333;
      font-size: 14px;
      border-bottom: 2px solid #ddd;
    }
    
    .price-table td {
      padding: 10px;
      text-align: center;
      font-size: 13px;
      color: #555;
      border-bottom: 1px solid #eee;
    }
    
    .price-table tr:last-child td {
      border-bottom: none;
    }
    
    /* Pizza Items */
    .pizza-list {
      margin-top: 30px;
    }
    
    .pizza-item {
      display: flex;
      align-items: flex-start;
      margin-bottom: 25px;
      padding-bottom: 25px;
      border-bottom: 1px dotted #ddd;
    }
    
    .pizza-item:last-child {
      border-bottom: none;
      margin-bottom: 0;
      padding-bottom: 0;
    }
    
    .pizza-image {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: #f0f0f0;
      border: 2px solid #ddd;
      margin-right: 20px;
      flex-shrink: 0;
      position: relative;
      overflow: hidden;
    }
    
    .pizza-image::before {
      content: '🍕';
      font-size: 40px;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }
    
    .pizza-info {
      flex: 1;
    }
    
    .pizza-name {
      font-size: 16px;
      font-weight: bold;
      color: #333;
      margin-bottom: 6px;
    }
    
    .pizza-desc {
      font-size: 13px;
      color: #666;
      line-height: 1.5;
    }
  </style>
  <meta name="pdf:generator" content="Dompdf" />
  <meta name="pdf:title" content="Our Menu - Food & Drinks" />
  <meta name="pdf:subject" content="Pizza Restaurant Menu" />
</head>
<body>
  <!-- Dark Header -->
  <div class="header-dark">
    <div class="menu-title">Our Menu</div>
    <div class="food-drinks">FOOD & DRINKS</div>
    <div class="header-decoration">🍅 🍅</div>
  </div>
  
  <!-- Main Content -->
  <div class="main-content">
    <!-- Paulo's Super Special -->
    <div class="special-section">
      <div class="special-title">Paulo's Super Special</div>
      <table class="price-table">
        <thead>
          <tr>
            <th style="text-align: left;">Pizza</th>
            <th>Small</th>
            <th>Medium</th>
            <th>Large</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="text-align: left;">2 for 1 Pizza</td>
            <td>$26.99</td>
            <td>$32.99</td>
            <td>$37.99</td>
          </tr>
          <tr>
            <td style="text-align: left;">Single Pizza</td>
            <td>$15.99</td>
            <td>$18.99</td>
            <td>$21.99</td>
          </tr>
          <tr>
            <td style="text-align: left;">ALL BUT THE COOK (2 for 1)</td>
            <td>$28.99</td>
            <td>$35.99</td>
            <td>$41.99</td>
          </tr>
        </tbody>
      </table>
    </div>
    
    <!-- Pizza Listings -->
    <div class="pizza-list">
      <div class="pizza-item">
        <div class="pizza-image"></div>
        <div class="pizza-info">
          <div class="pizza-name">House Special</div>
          <div class="pizza-desc">Pepperoni, Canadian Ham, Mushrooms, Olives, Onions, Green Peppers, Cheese & Tomato Sauce.</div>
        </div>
      </div>
      
      <div class="pizza-item">
        <div class="pizza-image"></div>
        <div class="pizza-info">
          <div class="pizza-name">The Vegetarian</div>
          <div class="pizza-desc">Olives, Mushrooms, Pineapple, Green Peppers, Tomatoes, Onions, Cheese & Tomato Sauce.</div>
        </div>
      </div>
      
      <div class="pizza-item">
        <div class="pizza-image"></div>
        <div class="pizza-info">
          <div class="pizza-name">Cook's Special</div>
          <div class="pizza-desc">Lean Beef, Tomatoes, Onions, Feta Cheese, Cheese & Tomato Sauce.</div>
        </div>
      </div>
      
      <div class="pizza-item">
        <div class="pizza-image"></div>
        <div class="pizza-info">
          <div class="pizza-name">Meat Lovers</div>
          <div class="pizza-desc">Salami, Pepperoni, Ham, Lean Beef, Bacon, Cheese & Tomato Sauce.</div>
        </div>
      </div>
      
      <div class="pizza-item">
        <div class="pizza-image"></div>
        <div class="pizza-info">
          <div class="pizza-name">Power Pizza</div>
          <div class="pizza-desc">Crumbled Bacon, Fresh Mushrooms, Ground Beef, Italian Sausage, Cheese & Tomato Sauce.</div>
        </div>
      </div>
      
      <div class="pizza-item">
        <div class="pizza-image"></div>
        <div class="pizza-info">
          <div class="pizza-name">Spinach Special</div>
          <div class="pizza-desc">Spinach, Tomatoes, Feta cheese, Cheese & Tomato Sauce.</div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
HTML;
})();

// Configure Dompdf
$options = new Options();
$options->set('isRemoteEnabled', false);
$options->set('defaultFont', 'DejaVu Sans');
$options->set('isHtml5ParserEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($menuHtml, 'UTF-8');
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

// Stream PDF to browser (inline by default; append ?download=1 to force download)
$filename = 'our-menu-food-drinks.pdf';
$forceDownload = isset($_GET['download']) && $_GET['download'] === '1';
$dompdf->stream($filename, ['Attachment' => $forceDownload ? 1 : 0]);

exit(0);
?>



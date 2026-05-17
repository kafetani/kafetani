<?php
// Definisikan BASE_URL jika belum ada (bisa juga di-set dari koneksi.php)
if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $path     = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
    define('BASE_URL', $protocol . '://' . $host . $path . '/');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kafetani — Farm to Table Cafe & Market</title>
<link rel="icon" type="image/svg+xml" href="/kafetani/assets/img/favicon.svg">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
/* CSS copied from reference index.html for consistent look */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --cream:#F7F3EC;
  --cream2:#EFE8D9;
  --brown:#3B2A1A;
  --brown2:#6B4C30;
  --green:#2D5016;
  --green2:#4A7C23;
  --green3:#7BAD45;
  --green-light:#EAF0DC;
  --amber:#C8883A;
  --amber-light:#F5ECD8;
  --text:#2A1F12;
  --text-mid:#7A6550;
  --text-light:#A9967E;
  --border:#D9CEBC;
  --ff-display:'Cormorant Garamond',serif;
  --ff-body:'DM Sans',sans-serif;
}
html{font-size:16px;scroll-behavior:smooth}
body{background:var(--cream);color:var(--text);font-family:var(--ff-body);font-weight:400;line-height:1.6;min-height:100vh}

/* NAV */
.main-nav{position:fixed;top:0;left:0;right:0;z-index:100;background:var(--cream);border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;padding:0 2.5rem;height:60px}
.nav-logo{display:flex;align-items:center;cursor:pointer;text-decoration:none}
.nav-links{display:flex;gap:2rem;align-items:center}
.nav-link{font-size:.85rem;font-weight:300;color:var(--text-mid);cursor:pointer;letter-spacing:.04em;text-transform:uppercase;transition:color .2s;text-decoration:none;font-family:var(--ff-body)}
.nav-link:hover,.nav-link.active{color:var(--green)}
.nav-cart{display:flex;align-items:center;gap:.4rem;background:var(--green);color:#fff;border:none;padding:.45rem 1rem;font-family:var(--ff-body);font-size:.8rem;font-weight:500;cursor:pointer;letter-spacing:.04em;position:relative;transition:background .2s}
.nav-cart:hover{background:var(--green2)}
.cart-badge{background:var(--amber);color:#fff;border-radius:50%;width:18px;height:18px;font-size:.65rem;display:flex;align-items:center;justify-content:center;font-weight:500}

/* Common Page Styles */
.page{padding-top:60px;min-height:100vh;animation:fadeUp .4s ease}
@keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}

/* ══ HOME PAGE ══ */
.hero{display:grid;grid-template-columns:1fr 1fr;min-height:calc(100vh - 60px)}
.hero-left{padding:5rem 3rem 4rem 3.5rem;display:flex;flex-direction:column;justify-content:center;background:var(--cream)}
.hero-tag{font-size:.75rem;letter-spacing:.18em;text-transform:uppercase;color:var(--text-light);margin-bottom:1.2rem;display:flex;align-items:center;gap:.6rem}
.hero-tag::before{content:'';width:28px;height:1px;background:var(--text-light)}
.hero-title{font-family:var(--ff-display);font-size:4.2rem;line-height:1.05;font-weight:300;color:var(--brown);margin-bottom:1.4rem}
.hero-title em{font-style:italic;color:var(--green)}
.hero-desc{font-size:.95rem;color:var(--text-mid);line-height:1.8;max-width:400px;margin-bottom:2.5rem;font-weight:300}
.hero-actions{display:flex;gap:1rem;flex-wrap:wrap}
.btn-primary{background:var(--green);color:#fff;border:none;padding:.75rem 1.8rem;font-family:var(--ff-body);font-size:.85rem;font-weight:500;cursor:pointer;letter-spacing:.04em;transition:background .2s}
.btn-primary:hover{background:var(--green2)}
.btn-outline{background:transparent;color:var(--brown);border:1px solid var(--brown);padding:.75rem 1.8rem;font-family:var(--ff-body);font-size:.85rem;cursor:pointer;letter-spacing:.04em;transition:all .2s}
.btn-outline:hover{background:var(--brown);color:#fff}
.hero-right{background:var(--green);position:relative;overflow:hidden;display:flex;align-items:center;justify-content:center}
.hero-pattern{position:absolute;inset:0;opacity:.07}
.hero-visual{position:relative;z-index:1;text-align:center;padding:3rem}
.hero-circle{width:260px;height:260px;border-radius:50%;background:var(--cream2);display:flex;flex-direction:column;align-items:center;justify-content:center;margin:0 auto 2rem;border:1px solid rgba(255,255,255,.15);position:relative;overflow:hidden}
.hero-circle-icon{width:100%;height:100%;margin:0;overflow:hidden;border-radius:50%;position:absolute;inset:0}
.hero-circle-label{font-family:var(--ff-display);font-size:1.4rem;font-weight:300;color:#fff;position:relative;z-index:2;background:rgba(42,31,18,.6);padding:.2rem 1.5rem;border-radius:20px}
.hero-pills{display:flex;gap:.6rem;justify-content:center;flex-wrap:wrap}
.hero-pill{background:rgba(255,255,255,.12);color:#fff;padding:.35rem .9rem;font-size:.78rem;border:1px solid rgba(255,255,255,.2);letter-spacing:.04em}

.home-stats{display:grid;grid-template-columns:repeat(3,1fr);border-top:1px solid var(--border);border-bottom:1px solid var(--border)}
.stat{padding:2rem;text-align:center;border-right:1px solid var(--border)}
.stat:last-child{border-right:none}
.stat-num{font-family:var(--ff-display);font-size:2.8rem;font-weight:300;color:var(--green);display:block}
.stat-label{font-size:.8rem;color:var(--text-light);letter-spacing:.08em;text-transform:uppercase;margin-top:.3rem}

.home-section{padding:4rem 3.5rem}
.section-header{display:flex;align-items:baseline;justify-content:space-between;margin-bottom:2rem}
.section-title{font-family:var(--ff-display);font-size:2.2rem;font-weight:300;color:var(--brown)}
.section-link{font-size:.8rem;color:var(--green);cursor:pointer;letter-spacing:.04em;text-decoration:underline;background:none;border:none;font-family:var(--ff-body)}

.featured-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem}
.feat-card{background:#fff;border:1px solid var(--border);overflow:hidden;cursor:pointer;transition:transform .2s,box-shadow .2s}
.feat-card:hover{transform:translateY(-4px);box-shadow:0 12px 32px rgba(45,80,22,.1)}
.feat-thumb{height:160px;display:flex;align-items:center;justify-content:center;font-size:3rem;position:relative}
.feat-thumb-cafe{background:var(--amber-light)}
.feat-thumb-market{background:var(--green-light)}
.feat-body{padding:1.1rem}
.feat-tag{font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;color:var(--text-light);margin-bottom:.4rem}
.feat-name{font-family:var(--ff-display);font-size:1.2rem;font-weight:400;color:var(--brown);margin-bottom:.3rem}
.feat-price{font-size:.9rem;color:var(--green);font-weight:500}

/* ══ PAGE HEADERS ══ */
.page-header{background:var(--green);color:#fff;padding:3.5rem 3.5rem 2.5rem;position:relative;overflow:hidden}
.page-header::after{content:'';position:absolute;right:-60px;top:-60px;width:240px;height:240px;border-radius:50%;border:1px solid rgba(255,255,255,.08)}
.page-header::before{content:'';position:absolute;right:60px;bottom:-80px;width:160px;height:160px;border-radius:50%;border:1px solid rgba(255,255,255,.06)}
.page-header-label{font-size:.72rem;letter-spacing:.2em;text-transform:uppercase;opacity:.6;margin-bottom:.8rem}
.page-header-title{font-family:var(--ff-display);font-size:3rem;font-weight:300;line-height:1.1;margin-bottom:.6rem}
.page-header-sub{font-size:.9rem;opacity:.7;font-weight:300}

.filter-bar{background:#fff;border-bottom:1px solid var(--border);padding:0 3.5rem;display:flex;gap:0;overflow-x:auto}
.filter-tab{padding:.9rem 1.4rem;font-size:.82rem;cursor:pointer;color:var(--text-mid);border-bottom:2px solid transparent;white-space:nowrap;transition:all .2s;background:none;border-top:none;border-left:none;border-right:none;font-family:var(--ff-body);letter-spacing:.02em}
.filter-tab.active{color:var(--green);border-bottom-color:var(--green);font-weight:500}
.filter-tab:hover{color:var(--green)}

.products-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1.5rem;padding:2.5rem 3.5rem}
.product-card{background:#fff;border:1px solid var(--border);cursor:pointer;transition:transform .2s,box-shadow .2s;position:relative}
.product-card:hover{transform:translateY(-3px);box-shadow:0 8px 24px rgba(45,80,22,.1)}
.product-thumb{height:150px;display:flex;align-items:center;justify-content:center;font-size:2.8rem;background:var(--cream2)}
.product-thumb.green{background:var(--green-light)}
.product-body{padding:1rem}
.product-cat{font-size:.68rem;letter-spacing:.12em;text-transform:uppercase;color:var(--text-light);margin-bottom:.3rem}
.product-name{font-family:var(--ff-display);font-size:1.1rem;font-weight:400;color:var(--brown);margin-bottom:.2rem}
.product-desc{font-size:.78rem;color:var(--text-light);line-height:1.5;margin-bottom:.8rem}
.product-footer{display:flex;align-items:center;justify-content:space-between}
.product-price{font-size:.95rem;color:var(--green);font-weight:500}
.add-btn{background:var(--green);color:#fff;border:none;width:28px;height:28px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:1.2rem;transition:background .2s;flex-shrink:0}
.add-btn:hover{background:var(--green2)}
.add-btn.added{background:var(--amber)}

/* ══ MARKET PAGE ══ */
.market-layout{display:grid;grid-template-columns:260px 1fr}
.market-sidebar{background:#fff;border-right:1px solid var(--border);padding:2rem;min-height:calc(100vh - 60px - 140px)}
.sidebar-title{font-size:.72rem;letter-spacing:.14em;text-transform:uppercase;color:var(--text-light);margin-bottom:1rem}
.farmer-card{display:flex;align-items:center;gap:.8rem;padding:.8rem;border:1px solid transparent;cursor:pointer;transition:all .2s;margin-bottom:.5rem}
.farmer-card:hover,.farmer-card.active{background:var(--green-light);border-color:var(--border)}
.farmer-avatar{width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0}
.farmer-avatar.a1{background:#FDE8D8}
.farmer-avatar.a2{background:#DFF2E1}
.farmer-avatar.a3{background:#E8E3F8}
.farmer-info-name{font-size:.9rem;font-weight:500;color:var(--brown)}
.farmer-info-loc{font-size:.75rem;color:var(--text-light)}
.market-products{padding:2rem 2.5rem}
.market-banner{background:var(--green);color:#fff;padding:1.5rem 2rem;margin-bottom:2rem;display:flex;align-items:center;justify-content:space-between}
.market-banner-text h3{font-family:var(--ff-display);font-size:1.5rem;font-weight:300;margin-bottom:.2rem}
.market-banner-text p{font-size:.82rem;opacity:.75;font-weight:300}
.market-banner-icon{font-size:2.5rem;opacity:.6}
.market-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1.2rem}

/* ══ CART PANEL ══ */
.cart-overlay{position:fixed;inset:0;background:rgba(42,31,18,.4);z-index:200;opacity:0;pointer-events:none;transition:opacity .3s}
.cart-overlay.open{opacity:1;pointer-events:all}
.cart-panel{position:fixed;right:0;top:0;bottom:0;width:380px;background:var(--cream);z-index:201;transform:translateX(100%);transition:transform .3s ease;display:flex;flex-direction:column;border-left:1px solid var(--border)}
.cart-panel.open{transform:translateX(0)}
.cart-top{padding:1.5rem;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
.cart-top h2{font-family:var(--ff-display);font-size:1.6rem;font-weight:300}
.cart-close{background:none;border:none;font-size:1.4rem;cursor:pointer;color:var(--text-mid);padding:.2rem}
.cart-items{flex:1;overflow-y:auto;padding:1.2rem}
.cart-empty{text-align:center;padding:3rem 1rem;color:var(--text-light)}
.cart-empty-icon{font-size:3rem;margin-bottom:1rem}
.cart-empty p{font-size:.9rem}
.cart-item{display:flex;gap:1rem;padding:.9rem 0;border-bottom:1px solid var(--border)}
.cart-item-icon{width:48px;height:48px;background:var(--cream2);display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0}
.cart-item-info{flex:1}
.cart-item-name{font-size:.9rem;font-weight:500;color:var(--brown);margin-bottom:.2rem}
.cart-item-price{font-size:.82rem;color:var(--green)}
.cart-item-qty{display:flex;align-items:center;gap:.6rem;margin-top:.5rem}
.qty-btn{width:24px;height:24px;border:1px solid var(--border);background:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.9rem;color:var(--text-mid);transition:all .2s}
.qty-btn:hover{background:var(--green);color:#fff;border-color:var(--green)}
.qty-num{font-size:.85rem;font-weight:500;min-width:16px;text-align:center}
.cart-remove{background:none;border:none;color:var(--text-light);cursor:pointer;font-size:.75rem;align-self:flex-start}
.cart-remove:hover{color:#c0392b}
.cart-bottom{padding:1.5rem;border-top:1px solid var(--border)}
.cart-row{display:flex;justify-content:space-between;font-size:.85rem;margin-bottom:.6rem;color:var(--text-mid)}
.cart-total{display:flex;justify(font-size:1.1rem;font-weight:500;color:var(--brown);margin:1rem 0)}
.checkout-btn{width:100%;background:var(--green);color:#fff;border:none;padding:.9rem;font-family:var(--ff-body);font-size:.9rem;font-weight:500;cursor:pointer;letter-spacing:.04em;transition:background .2s}
.checkout-btn:hover{background:var(--green2)}

/* ══ ORDER SUCCESS ══ */
.order-success{display:none;position:fixed;inset:0;background:rgba(42,31,18,.5);z-index:300;align-items:center;justify-content:center}
.order-success.show{display:flex}
.success-box{background:var(--cream);padding:3rem;max-width:420px;width:90%;text-align:center}
.success-icon{font-size:3rem;margin-bottom:1rem}
.success-title{font-family:var(--ff-display);font-size:2rem;font-weight:300;color:var(--green);margin-bottom:.6rem}
.success-text{font-size:.9rem;color:var(--text-mid);margin-bottom:2rem;line-height:1.7}
.success-close{background:var(--green);color:#fff;border:none;padding:.8rem 2rem;font-family:var(--ff-body);font-size:.9rem;cursor:pointer;width:100%;transition:background .2s}
.success-close:hover{background:var(--green2)}

.admin-layout{display:grid;grid-template-columns:240px 1fr;min-height:100vh;}
.admin-sidebar{background:var(--brown);color:#fff;padding:2rem;display:flex;flex-direction:column;gap:1.5rem;position:sticky;top:0;height:100vh;}
.admin-nav{display:flex;flex-direction:column;gap:.8rem;}
.admin-nav-link{color:#fff;text-decoration:none;font-size:.9rem;opacity:.7;transition:opacity .2s;}
.admin-nav-link:hover{opacity:1;}
.admin-nav-link.active{color:var(--amber);opacity:1;font-weight:500;}

/* toast */
.toast{position:fixed;bottom:2rem;left:50%;transform:translateX(-50%) translateY(100px);background:var(--brown);color:#fff;padding:.7rem 1.4rem;font-size:.82rem;z-index:400;transition:transform .3s;white-space:nowrap}
.toast.show{transform:translateX(-50%) translateY(0)}

/* AUTH PAGES */
.auth-container{max-width:400px;margin:100px auto;padding:2rem;background:#fff;border:1px solid var(--border);border-radius:4px}
.auth-title{font-family:var(--ff-display);font-size:2rem;font-weight:300;color:var(--brown);margin-bottom:1.5rem;text-align:center}
.form-group{margin-bottom:1rem}
.form-group label{display:block;font-size:.8rem;color:var(--text-mid);margin-bottom:.4rem}
.form-group input{width:100%;padding:.7rem;border:1px solid var(--border);font-family:var(--ff-body);font-size:.9rem}
.form-group input:focus{outline:none;border-color:var(--green)}
.auth-btn{width:100%;background:var(--green);color:#fff;border:none;padding:.8rem;cursor:pointer;margin-top:1rem;font-weight:500;font-family:var(--ff-body)}
.auth-link{display:block;text-align:center;font-size:.8rem;color:var(--text-mid);margin-top:1rem;text-decoration:none}
.auth-link:hover{text-decoration:underline}

footer{background:var(--brown);color:#fff;padding:4rem 3.5rem 2rem;margin-top:2rem}
.footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1.5fr;gap:4rem;margin-bottom:4rem}
.footer-logo{height:70px;margin-bottom:1.5rem;filter:brightness(0) invert(1)}
.footer-desc{font-size:.85rem;color:rgba(255,255,255,.6);line-height:1.8;margin-bottom:1.5rem}
.footer-title{font-family:var(--ff-display);font-size:1.1rem;font-weight:400;color:var(--amber);margin-bottom:1.5rem;letter-spacing:.05em}
.footer-links{list-style:none}
.footer-link{display:block;color:rgba(255,255,255,.7);text-decoration:none;font-size:.85rem;margin-bottom:.8rem;transition:color .2s}
.footer-link:hover{color:#fff}
.footer-contact{font-size:.85rem;color:rgba(255,255,255,.6);line-height:1.8;margin-bottom:.8rem;display:flex;align-items:center;gap:.6rem}
.footer-bottom{padding-top:2rem;border-top:1px solid rgba(255,255,255,.1);display:flex;justify-content:space-between;align-items:center;font-size:.75rem;color:rgba(255,255,255,.4)}
@media (max-width:968px){.footer-grid{grid-template-columns:1fr 1fr;gap:2rem}}
@media (max-width:568px){.footer-grid{grid-template-columns:1fr}}
</style>
</head>
<body>

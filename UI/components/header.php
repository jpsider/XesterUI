<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <!-- Need to Create one! -->
        <!-- TODO -->
        <link rel="icon" href="img/favicon.jpg">
        <!-- CLARITY CSS-->
		<link rel="stylesheet" href="components/css/clarity-ui-dark.min.css" title="dark" type="text/css" />
		<link rel="alternate stylesheet" href="components/css/clarity-ui.min.css" title="light" type="text/css"/>
        <link rel="stylesheet" href="components/css/clarity-icons.min.css">
        <!-- Datatables JS-->
        <script src="components/js/jquery.min.js"></script>
        <script src="components/js/datatables.min.js"></script>
        <!-- Clarity JS -->
        <script src="components/js/custom-elements.min.js"></script>
        <script src="components/js/clarity-icons.min.js"></script>
        <script src="components/js/app.js"></script>
        <!-- Custom JS -->
        <script src="components/js/SwitchStyles.js"></script>
        <script src="components/js/refresh.js"></script>
        <!-- Custom CSS -->
        <link rel="stylesheet" href="components/css/custom-datatables.jquery.min.css">
        <link rel="stylesheet" href="components/css/custom-buttons.css">
        <title>XesterUI</title>
    </head>
    <script> 
	$(document).ready(function() {
            $('#example').dataTable({
                "dom": '<"wrapper"fltip>'
            });
	});
    </script>
    <body onload="set_style_from_cookie()">
        <div class="main-container">
            <header class="header header-3">
                <div class="branding">
                    <a href="index.php" class="nav-link">
                        <span class="title">XesterUI</span>
                    </a>
                </div>
                <div class="header-nav">
                    <a href="index.php" class="active nav-link nav-text">Plugin01</a>
                    <a href="index.php" class="nav-link nav-text">Plugin02</a>
                </div>
                <div class="header-actions">
                    <div class="btn-group">
                        <button class="btn btn-inverse btn-sm" type="button" onclick="switch_style('dark');return false;" name="theme" value="Dark Theme" id="dark"><clr-icon class="is-solid" size="12" shape="cloud"></clr-icon> Dark Theme</button>
                        <button class="btn btn-inverse btn-sm" type="button" onclick="switch_style('light');return false;" name="theme" value="Light Theme" id="light"><clr-icon class="is-solid" size="12" shape="lightbulb"></clr-icon> Light Theme</button>
                    </div>
                    <div class="toggle-switch">
                        <input type="checkbox" id="reloadCB" onclick="toggleAutoRefresh(this);">
                        <label for="reloadCB"><clr-icon class="is-solid" size="12" shape="refresh"></clr-icon> Refresh</label>
                    </div>
                    <a href="https://github.com/jpsider/xesterui" class="nav-link nav-icon" target="_blank">
                        <clr-icon shape="cog"></clr-icon>
                    </a>
                </div>
            </header>
	    <div class="content-container">

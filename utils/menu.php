<?php
function Menu($active)
{
    $menu = [
        'Inicio' => '../home/',
        'Nosotros' => '../about/',
        'Productos' => '../product/',
        'Contacto' => '../contact/'
    ];
    $menuHTML = '<div class="content-menu">
        <div class="logo">
            <img src="../../assets/logo.png" alt="Logo">
        </div>
        <nav class="nav">
            <div class="nav-menu">
                <button class="btn-menu">
                    <i class="bi bi-list"></i>
                </button>
            </div>
            <div class="nav-list">
                <ul class="nav-ul">';
    foreach ($menu as $key => $value) {
        $menuHTML .= '<li class="nav-item">
                        <a class="nav-link ' . ($active == $key ? 'active' : '') . '" href="' . $value . '">' . $key . '</a>
                    </li>';
    }
    $menuHTML .= '</ul>
            </div>
        </nav>
    </div>';

    return $menuHTML;
}
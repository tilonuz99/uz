<?php
$admin = '1002919466';
$token = 'Token';
$bot = 'TIMAKRUDBET_BOT';

//Ushbu bot kodi Temurriy Dakentiy Php_Builder tomonidan yangi yil sovgasi uchun tarqaldi//
//Kanallarimiz; @UzBotMaster @Uz Coderlar//

function bot($method,$datas=[]){
global $token;
    $url = "https://api.telegram.org/bot".$token."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}

$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$mid = $message->message_id;
$data = $update->callback_query->data;
$type = $message->chat->type;
$text = $message->text;
$cid = $message->chat->id;
$uid= $message->from->id;
$gname = $message->chat->title;
$left = $message->left_chat_member;
$new = $message->new_chat_member;
$name = $message->from->first_name;
$repid = $message->reply_to_message->from->id;
$repname = $message->reply_to_message->from->first_name;
$newid = $message->new_chat_member->id;
$leftid = $message->left_chat_member->id;
$newname = $message->new_chat_member->first_name;
$leftname = $message->left_chat_member->first_name;
$username = $message->from->username;
$fname = $message->from->first_name;
$cmid = $update->callback_query->message->message_id;
$cusername = $message->chat->username;
$repmid = $message->reply_to_message->message_id; 
$ccid = $update->callback_query->message->chat->id;
$cuid = $update->callback_query->message->from->id;
$cqid = $update->callback_query->id;

$photo = $update->message->photo;
$gif = $update->message->animation;
$video = $update->message->video;
$music = $update->message->audio;
$voice = $update->message->voice;
$sticker = $update->message->sticker;
$document = $update->message->document;
$for = $message->forward_from;
$forc = $message->forward_from_chat;

$cont = $message->contact;
$number = $message->contact->phone_number;
$name = $message->contact->first_name;

mkdir("pul");
mkdir("odam");
mkdir("qiwi");
$pul = file_get_contents("pul/$cid.txt");
$odam = file_get_contents("odam/$cid.dat");
$key = json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[['text'=>"💰Пул ишлаш"],['text'=>"💰 Хисоб"]],
[['text'=>"Қандай пул ишласа бўлади ❓"]],
]
]);

$key2 = json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[['text'=>"◀ Ortga"]],
]
]);
//Ushbu bot kodi Temurriy Dakentiy Php_Builder tomonidan yangi yil sovgasi uchun tarqaldi//
//Kanallarimiz; @UzBotMaster @Uz Coderlar//
$key1 = json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[['text'=>"💸 Пулни Олиш"]],
[['text'=>"◀ Ortga"]],
]
]);

$boshlash = json_encode([
'resize_keyboard'=>true,
'keyboard'=>[
[['text'=>"◀ Ortga"]],
]
]);

if($text=="/start"){
$pul = file_get_contents("pul/$cid.txt");
$mm=$pul+0;
file_put_contents("pul/$cid.txt","$mm");
$odam = file_get_contents("odam/$cid.dat");
$kkd=$odam+0;
file_put_contents("odam/$cid.dat","$kkd");
bot('sendmessage',[
    'chat_id'=>$cid,
    'text'=>" <b> Biz Bilan Pul Ishlang! </b>",
    'parse_mode'=>'html',
    'reply_markup'=>$key
    ]);
}
if(mb_stripos($text,"/start $cid")!==false){
bot('sendMessage',[
      'chat_id'=>$cid,
      'text'=>"<b>ERROR</B>",
      'parse_mode'=>'html',
      'reply_markup'=>$key,
      ]);
}else{
      $idref = "pul/$ex.db";
      $idref2 = file_get_contents($idref);
      $id = "$cid\n";
      $handle = fopen($idref, 'a+');
      fwrite($handle, $id);
      fclose($handle);
if(mb_stripos($idref2,$cid) !== false ){
}else{
$pub=explode(" ",$text);
$ex=$pub[1];
$pul = file_get_contents("pul/$ex.txt");
$a=$pul+0.10;
file_put_contents("pul/$ex.txt","$a");
$odam = file_get_contents("odam/$ex.dat");
$b=$odam+1;
file_put_contents("odam/$ex.dat","$b");
bot('sendMessage',[
'chat_id'=>$cid,
'text'=>"
 <b>💰Пул ишлаш</b> тугмасини босинг🔘👈
 ",
'parse_mode'=>'html',
'reply_markup'=>$boshlash,
]);
bot('sendmessage',[
'chat_id'=>$ex,
'text'=>"👏 Tabriklaymiz! Siz Dostingizni botga taklif qildingiz!
0.10 ₽ ga ega bo'ldingiz!",
'parse_mode'=>'html',
'reply_markup'=>$key,
]);
}
}

if($text=="◀ Ortga"){
bot('sendmessage',[
'chat_id'=>$cid,
'text'=>"<a href='tg://user?id=$cid'> $fname </a>, Нима Киламиз?",
'parse_mode'=>'html',
'reply_markup'=>$key,
]);
}
//Ushbu bot kodi Temurriy Dakentiy Php_Builder tomonidan yangi yil sovgasi uchun tarqaldi//
//Kanallarimiz; @UzBotMaster @Uz Coderlar//
if($text=="✅ Pul ishlashni boshlash"){
bot('sendmessage',[
'chat_id'=>$cid,
'text'=>"<a href='tg://user?id=$cid'> $fname </a>, nima qilamiz?",
'parse_mode'=>'html',
'reply_markup'=>$key,
]);
}

if($text=="Қандай пул ишласа бўлади ❓"){
bot('sendmessage',[
'chat_id'=>$cid,
'text'=>"*Бунинг учун 
1. Ботимизнинг Пул ишлаш булимига кириб, кайси турдаги баланс учун пул ишлашингизни танлайсиз ва таркатиш тугмасини босасиз.
2. Таркатилган хаволанинг устига босиб дустингиз кириши ва ботга старт босиши шунингдек каналимизга аъзо булиши керак.
3. Сохта аккаунтлардан пул ишлаб булмайди! Ишланса хам ТУЛАНМАЙДИ!
4. Ишлаган пулингизни ечиб олсангиз ёки тренинг учун сарфласангиз*",
'parse_mode'=>'MarkDown',
'reply_markup'=>$key,
]);
}

if($text=="💰Пул ишлаш"){
bot('sendmessage',[
'chat_id'=>$cid,
'text'=>"🎈 $fname Сиз бу ботдан пул ишлашингиз учун сизга реферал ссылка такдим этилади! Сиз достларингизни уз реферал силкангиз орали чакира олсангиз бот сизга 0.10₽ тўлайди!

Сизнинг реферал силкангиз👇

t.me/$bot?start=$cid
Havolani quyidagi tugma orqali ham tarqatsa bo'ladi👇",
'parse_mode'=>'html',
'reply_markup'=>$key,
]);
}

//Ushbu bot kodi Temurriy Dakentiy Php_Builder tomonidan yangi yil sovgasi uchun tarqaldi//
//Kanallarimiz; @UzBotMaster @Uz Coderlar//
if($text=="💰 Хисоб"){
$pul = file_get_contents("pul/$cid.txt");
$odam = file_get_contents("odam/$cid.dat");
$odam1 = 20 - $odam;
bot('sendmessage',[
'chat_id'=>$cid,
'text'=>"<b>Сизнинш Хисоб:</b> <code>$pul</code>

<b>Сиз Таклиф Килган Азолар:</b> <code>$odam</code>

<b>Сиз Йана:</b> <code>$odam1</code> <b>одам таклиф етишингиз керак.</b>

<b>Минимални Йечищ:</b> 2₽ ",
'parse_mode'=>'html',
'reply_markup'=>$key1,
]);
}

if($text=="💸 Пулни Олиш"){
bot('sendmessage',[
'chat_id'=>$cid,
'text'=>"<b>Biz pul o'tkazishimiz kerak bo'lgan QIWI raqamni yozib yuboring bizga! Hech qanday qo'shimchalarsiz</b>
   <B>Namuna:</b> <code>998911234567</code>",
'parse_mode'=>'html',
'reply_markup'=>$key2,
]);
}

if(mb_stripos($text,"998")!==false){
file_put_contents("qiwi/$cid.txt","$text");
$qiwi=file_get_contents("qiwi/$cid.txt");
$pul = file_get_contents("pul/$cid.txt");
$odam = file_get_contents("odam/$cid.dat");
if($pul>=2){
bot('sendmessage',[
'chat_id'=>$cid,
'text'=>"👩‍💼 💰 $pul ₽
<code>---------------</code>
📥 Киви: <code>+$qiwi</code>
<code>---------------</code>
✅ To'lov qabul qilindi biz siz foydalanuvchilardan yuborilgan vakansiyalarni 48 soat ichida amalga oshiramiz !
<code>---------------</code>",
'parse_mode'=>'html',
'reply_markup'=>$key,
]);
bot('sendmessage',[
'chat_id'=>$admin,
'text'=>"<b>🔀 Telefon raqami uchun to'lov</b>
👤<b>Useri:</b> @$username
📲 <code>+$qiwi</code>
💰<b>Balans: $pul </b>
👥<b>Taklif qilgan odamlari: $odam</b>",
'parse_mode'=>'html',
]);
$pul = file_get_contents("pul/$cid.txt");
$k=$pul-$pul;
file_put_contents("pul/$cid.txt","$k");
$sum=file_get_contents("tolandi.txt");
$uio=$pul+$sum;
file_put_contents("tolandi.txt","$uio");
}else{
bot('sendmessage',[
'chat_id'=>$cid,
'text'=>"❌ <b>Sizda yetarli mablaģ yo'q!</b>

Pulingizni chiqarish uchun minimal miqdorda <b>2 ₽,</b> hozirgi paytda hisobingizda <b>$pul ₽.</b> Siz yana ko'proq do'stingizni taklif qilishingiz lozim.",
'parse_mode'=>'html',
'reply_markup'=>$key,
]);
}
}
//Ushbu bot kodi Temurriy Dakentiy Php_Builder tomonidan yangi yil sovgasi uchun tarqaldi//
//Kanallarimiz; @UzBotMaster @Uz Coderlar//
if((mb_stripos($text,"/add")!==false) and $cid==$admin){
$exx=explode("_",$text);
$ex1=$exx[1];
$ex2=$exx[2];
$pul = file_get_contents("pul/$ex1.txt");
$rr=$pul+$ex2;
file_put_contents("pul/$ex1.txt","$rr");
$pul = file_get_contents("pul/$ex1.txt");
$odam = file_get_contents("odam/$ex1.dat");
bot('sendmessage',[
'chat_id'=>$ex1,
'text'=>"<b>$fname tarafidan sizga $ex2 so'm berildi!</b>
<b>💰Balansingiz: $pul so'm bo'ldi!</b>",
'parse_mode'=>'html',
]);
}
if((mb_stripos($text,"/minus")!==false) and $cid==$admin){
$exxx=explode("_",$text);
$ex3=$exxx[1];
$ex4=$exxx[2];
$pul = file_get_contents("pul/$ex3.txt");
$rr=$pul-$ex4;
file_put_contents("pul/$ex3.txt","$rr");
$pul = file_get_contents("pul/$ex3.txt");
bot('sendmessage',[
'chat_id'=>$ex1,
'text'=>"<b>$fname Qoida buzarlik qilganingiz uchun sizdan $ex2 so'm olib tashlandi!</b>
💰<b>Balansingiz: $pul so'm</b>",
'parse_mode'=>'html',
]);
}
$lichka = file_get_contents("lichka.txt");
if($type=="private"){
if(strpos($lichka,"$cid") !==false){
}else{
file_put_contents("lichka.txt","$lichka\n$cid");
}
} 
$reply = $message->reply_to_message->text;
$rpl = json_encode([
           'resize_keyboard'=>false,
            'force_reply' => true,
            'selective' => true
        ]);
if($text=="/send" and $cid==$admin){
    bot('sendmessage',[
'chat_id'=>$admin,
'text'=>"Yozing...✏️",'parse_mode'=>"html",'reply_markup'=>$rpl
]);
}
    if($reply=="Yozing...✏️"){
        $lich = file_get_contents("lichka.txt");
        $lichka = explode("\n",$lich);
foreach($lichka as $uid){
    bot("sendmessage",[
        'chat_id'=>$uid,
        'text'=>"$text"]);
}
}
$sum=file_get_contents("tolandi.txt");
if($text=="stat"){
$soat = date('H:i', strtotime('5 hour'));
$sana = date('d-M-Y',strtotime('5 hour'));
$lich = substr_count($lichka,"\n");
bot('sendmessage',[
'chat_id'=>$cid,
'text'=>"📊 Статистика:</b>

<b>Бот Азолари</b> <code>$lich</code>
<b>Сиз таклиф етганлар:</b> <code>$odam</code>",
'parse_mode'=>'html',
'reply_markup'=>$key,
]);
}
//Ushbu bot kodi Temurriy Dakentiy Php_Builder tomonidan yangi yil sovgasi uchun tarqaldi//
//Kanallarimiz; @UzBotMaster @Uz Coderlar//
?>
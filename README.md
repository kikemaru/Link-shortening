# Link-shortening
 educational practice
<hr>
<h3>База данных:</h3>
<b>users:</b>
<table border="1">
<thead>
  <th>Название</th>
  <th>Тип данных</th>
  <th>Описание</th>
  </thead>
  <tbody>
    <tr>
      <td>id</td>
      <td>int(11)</td>
      <td>Идентификатор пользователя</td>
    </tr>
    <tr>
      <td>login</td>
      <td>varchar(20)</td>
      <td>Логин пользователя</td>
    </tr>
    <tr>
      <td>password</td>
      <td>varchar(60)</td>
      <td>Пароль пользователя (хэш)</td>
    </tr>
    <tr>
      <td>email</td>
      <td>varchar(30)</td>
      <td>Эл. Почта пользователя</td>
    </tr>
    <tr>
      <td>status</td>
      <td>int(1)</td>
      <td>Статус пользователя (0 - активен; 1 - заблокирован; 2 - администратор)</td>
    </tr>
   
  </tbody>
</table>

<b>link:</b>
<table>
  <thead>
    <th>Название</th>
  <th>Тип данных</th>
  <th>Описание</th>
  </thead>
  <tbody>
    <tr>
      <td>id</td>
      <td>int(11)</td>
      <td>Идентификатор ссылки</td>
    </tr>
     <tr>
      <td>code_link</td>
      <td>varchar(30)</td>
      <td>Созданный код для сокращенной ссылки</td>
    </tr>
     <tr>
      <td>redirect</td>
      <td>TEXT</td>
      <td>Сокращаемая ссылка</td>
    </tr>
     <tr>
      <td>id_user</td>
      <td>int(11)</td>
      <td>Создатель и владелец ссылки</td>
    </tr>
     <tr>
      <td>status</td>
      <td>int(1)</td>
      <td>Статус ссылки (0 - работает; 1 - отключена; 2 - заблокирована)</td>
    </tr>
  </tbody>
</table>
  <b>log:</b>
  <table>
    <thead>
      <th>Название</th>
      <th>Тип данных</th>
      <th>Описание</th>
    </thead>
    <tbody>
      <tr>
        <td>id</td>
        <td>int(11)</td>
        <td>Идентификатор записи</td>
      </tr>
      <tr>
        <td>id_link</td>
        <td>int(11)</td>
        <td>Идентификатор ссылки</td>
      </tr>
      <tr>
        <td>ip</td>
        <td>varchar(20)</td>
        <td>Ip адрес перешедший по ссылке</td>
      </tr>
      <tr>
        <td>referer</td>
        <td>TEXT</td>
        <td>Откуда перешли по ссылке</td>
      </tr>
      <tr>
        <td>client</td>
        <td>TEXT</td>
        <td>Устройство (браузер) в котором перешли по ссылке</td>
      </tr>
      <tr>
        <td>date_time</td>
        <td>datetime</td>
        <td>Дата и время когда перешли по ссылке</td>
      </tr>
    </tbody>
</table>

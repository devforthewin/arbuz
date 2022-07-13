<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Hello, world!</title>
</head>
<body>


<style>
    .main-container {
        margin-top: 30px;
    }

    .main-container > h2 {
        margin-bottom: 40px;
    }
</style>

<div class="container main-container">
    <h2>Гостевая книга</h2>

    <?php
        $entries = `curl -k caddy/guestbook`;
        $decoded = json_decode($entries, true);
    ?>

    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Имя</th>
            <th scope="col">Имейл</th>
            <th scope="col">Запись</th>
            <th scope="col">Дата</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($decoded as $item): ?>
        <tr>
            <td><?php echo $item['id']?></td>
            <td><?php echo $item['name']?></td>
            <td><?php echo $item['email']?></td>
            <td><?php echo $item['message']?></td>
            <td><?php echo $item['created_at']?></td>
        </tr>
        <?php endforeach;?>

        </tbody>
    </table>

    <br><br><br>
    <h3>Новая запись</h3>

    <form method="post" action="/guestbook">
        <div class="form-group">
            <label for="exampleInputPassword1">Имя</label>
            <input name="name" type="text" class="form-control" id="exampleInputPassword1" placeholder="">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Имейл</label>
            <input name="email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                   placeholder="">
        </div>

        <div class="form-group">
            <label for="msg">Запись</label>
            <textarea class="form-control" name="message" id="msg" cols="30" rows="10"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Добавить</button>
    </form>

    <br><br><br><br><br><br><br><br><br>
</div>


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
</body>
</html>

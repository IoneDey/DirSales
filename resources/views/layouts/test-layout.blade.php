<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
</head>

<body>

    <select id="test" name="state">
        <option value="AL">Alabama</option>
        <option value="WB">1 Wyoming</option>
        <option value="WC">2 Wyoming</option>
        <option value="WD">3 Wyoming</option>
        <option value="WE">4 Wyoming</option>
        <option value="WF">5 Wyoming</option>
        <option value="WG">6 Wyoming</option>
        <option value="WH">7 Wyoming</option>
        <option value="WI">8 Wyoming</option>
        <option value="WJ">9 Wyoming</option>
        <option value="WK">10 Wyoming</option>
        <option value="WL">11 Wyoming</option>
    </select>

    <select id="test1" name="state">
        <option value="AL">Alabama</option>
        <option value="WB">1 Wyoming</option>
        <option value="WC">2 Wyoming</option>
        <option value="WD">3 Wyoming</option>
        <option value="WE">4 Wyoming</option>
        <option value="WF">5 Wyoming</option>
        <option value="WG">6 Wyoming</option>
        <option value="WH">7 Wyoming</option>
        <option value="WI">8 Wyoming</option>
        <option value="WJ">9 Wyoming</option>
        <option value="WK">10 Wyoming</option>
        <option value="WL">11 Wyoming</option>
    </select>

    <script>
        $(document).ready(function() {
            $('#test').select2();
            $('#test1').select2();
        });
    </script>
</body>

</html>
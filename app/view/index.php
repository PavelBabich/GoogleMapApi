<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Google Maps</title>

    <link href="/public/styles/main.css" rel="stylesheet">
    <script src="/public/js/form.js"></script>
</head>
<body>
    <div class="heading">
        <h1>Google Maps</h1>
    </div>
    <div class="container">
        <div class="search-container">
            <form id="mapForm" action="map/search" autocomplete="off">
                <div class="autocomplete">
                    <input name="search" id="searchTextField" type="text" placeholder="Search">
                </div>
            </form>
            <div class="requests-table">
                <table>
                    <thead>
                    <tr>
                        <th scope="col">Requested place</th>
                        <th scope="col">Request time</th>
                    </tr>
                    </thead>
                </table>
                <div class="scroll-table">
                    <table>
                        <tbody>
                        <?php foreach($userRequests as $request) : ?>
                            <tr>
                                <td><?php echo $request['request'] ?></td>
                                <td><?php echo $request['timecreated'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="map-container">
            <div class="map">
                <img id="googleMap" src="<?php echo $mapUrl ?>" alt="">
            </div>
        </div>
    </div>
</body>
</html>
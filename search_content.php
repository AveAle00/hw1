<?php
    require_once 'checkSession.php';
    
    if (!isset($_GET["q"])) {
        if(checkSession()){
            header("Location: user_home.php");
        } else{
            header("Location: index_home.php");
        }
        exit;
    }
    function makeCurlRequest($curl, $url, $fields)
    {
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $fields
        ]);
        return json_decode(curl_exec($curl), true);
    }

    $client_id = 'aqicdi2c2nm3ebepz0rcjgamjimgll';
    $client_secret = 'r7qm416dgmrg3m7s9ueaj6i9umo7iq';
    
    // ACCESS TOKEN
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://id.twitch.tv/oauth2/token');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    # POST
    curl_setopt($curl, CURLOPT_POST, 1);
    # BODY E HEADER RICHIESTA
    curl_setopt($curl, CURLOPT_POSTFIELDS, 'grant_type=client_credentials&client_id=' . $client_id . '&client_secret=' . $client_secret);
    $token = json_decode(curl_exec($curl), true);
    curl_close($curl);
    
    // QUERY DI RICERCA
    $query = $_GET["q"];
    $url = 'https://api.igdb.com/v4/games';
    $curl = curl_init();
    
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => 'fields cover, name, genres, first_release_date, platforms; search "' . $query . '"; limit 8;',
        CURLOPT_HTTPHEADER => ['Client-ID:' . $client_id, 'Authorization: Bearer ' . $token['access_token']]
    ]);
    $res = json_decode(curl_exec($curl), true);
    curl_close($curl);
    
    

    for ($i = 0; $i < count($res); $i++) {
        
    }

    // QUERY PER MODIFICARE CAMPO GENRES, PLATFORMS

    // Preparazione connessione GENRES
    $url_genres = 'https://api.igdb.com/v4/genres';
    $curl_genres = curl_init();
    
    // Preparazione connessione PLATFORMS
    $url_platforms = 'https://api.igdb.com/v4/platforms';
    $curl_platforms = curl_init();

    //Preparazione campo COVER
    $url_cover = 'https://api.igdb.com/v4/covers';
    $curl_cover = curl_init();

    
    curl_setopt_array($curl_genres, [
        CURLOPT_HTTPHEADER => ['Client-ID:' . $client_id, 'Authorization: Bearer ' . $token['access_token']]
    ]);
    
    curl_setopt_array($curl_platforms, [
        CURLOPT_HTTPHEADER => ['Client-ID:' . $client_id, 'Authorization: Bearer ' . $token['access_token']]
    ]);

    curl_setopt_array($curl_cover, [
        CURLOPT_HTTPHEADER => ['Client-ID:' . $client_id, 'Authorization: Bearer ' . $token['access_token']]
    ]);

    

    for ($i = 0; $i < count($res); $i++) {
        # COVER
        if(array_key_exists('cover',$res[$i])){
        $res_cover = makeCurlRequest($curl_cover, $url_cover, 'fields image_id; where id = ' . $res[$i]['cover'] . ';');
        // Modifica Cover
            $res[$i]['cover'] = 'https://images.igdb.com/igdb/image/upload/t_cover_big/' . $res_cover[0]['image_id'] . '.jpg';
        }else{
            $res[$i]['cover'] ='image/no-image.png';
        }
        # GENRES
        if(array_key_exists('genres',$res[$i])){
            $values=array_values($res[$i]['genres']);
            $gen_query=implode(', ', $values);
            $res_genres = makeCurlRequest($curl_genres, $url_genres, 'fields name; where id = (' . $gen_query . ');');
            // Modifica Genres
            for($j = 0 ; $j < count($res_genres); $j++){
                $res[$i]['genres'][$j] = $res_genres[$j]['name'];  
            }
        }

        # PLATFORMS
        if(array_key_exists('platforms',$res[$i])){
            $values=array_values($res[$i]['platforms']);
            $plat_query=implode(', ', $values);
            $res_platforms = makeCurlRequest($curl_platforms, $url_platforms, 'fields name; where id = (' . $plat_query . ');');
            // Modifica Platforms
            for($j = 0 ; $j < count($res_platforms); $j++){
                $res[$i]['platforms'][$j] = $res_platforms[$j]['name'];  
            }
        }
    }
    
    curl_close($curl_cover);
    curl_close($curl_genres);
    curl_close($curl_platforms);
    echo json_encode($res);
?>
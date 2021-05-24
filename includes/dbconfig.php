<?php 
    require __DIR__.'/vendor/autoload.php'; 
    
    use Kreait\Firebase\Factory; 
    use Kreait\Firebase\ServiceAccount;  
    
    $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/sahem-ea363-firebase-adminsdk-qiq46-0de350437e.json');
    $firebase = (new Factory)
        ->withServiceAccount($serviceAccount)
        ->withDatabaseUri('https://sahem-ea363-default-rtdb.asia-southeast1.firebasedatabase.app')
        ->create();

    $database = $firebase->getDatabase();  
?>

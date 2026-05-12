<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - TopupKing</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: Arial, sans-serif; 
            background: #f0f2f5;
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            padding: 20px;
        }
        .container { 
            background: white; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
            width: 100%;
            max-width: 400px; 
        }
        h2 { 
            text-align: center; 
            color: #1c1e21; 
            margin-bottom: 25px; 
        }
        .form-control {
            margin-bottom: 15px;
        }
        input { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #dddfe2; 
            border-radius: 6px; 
            font-size: 15px;
        }
        input:focus {
            outline: none;
            border-color: #1877f2;
        }
        .btn { 
            width: 100%; 
            padding: 12px; 
            background: #42b72a;
            color: white; 
            border: none; 
            border-radius: 6px; 
            font-size: 17px;
            font-weight: bold;
            cursor: pointer; 
            margin-top: 10px;
        }
        .btn:hover {
            background: #36a

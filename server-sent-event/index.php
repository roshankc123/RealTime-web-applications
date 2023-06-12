<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>server-sent events</title>
    <script src="https://unpkg.com/react@18/umd/react.development.js" crossorigin></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js" crossorigin></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
</head>
<body onload="">
    <div id="main"></div>
</body>
<script type='text/babel'>

    const container = document.getElementById('main');
    var doc;
    var root;
        function RenderElement(props){
            return <p>{props.message}</p>
        }
        function Load(message){
            var doc = document.createElement('span')
            var root = ReactDOM.createRoot(doc)
            root.render(<RenderElement message={message}/>)
            container.appendChild(doc)
        }

        if(typeof(EventSource) != 'undefined'){
            var source = new EventSource('/server.php');
            source.onopen = (event) => {
                console.log('opened')
            }
            source.addEventListener('hello', (event) => {
                console.log('hello came')
            })
            source.onmessage = function(event){
                console.log(event)
                Load(event.data)
            }
            
        }else {
            document.getElementById("main").innerHTML = "Sorry, your browser does not support server-sent events...";
        }
    </script>

</html>
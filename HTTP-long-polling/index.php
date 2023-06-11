<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HTTP-Long-polling</title>
    <script src="https://unpkg.com/react@18/umd/react.development.js" crossorigin></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js" crossorigin></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
</head>
<body onload="">
    <div id="setid">
        <input type="text" id='id-inp'>
        <button onclick="setid()">send</button>
    </div>
        <input type="text" id='inp'>
        <button onclick="send()">send</button>
    <div id="main"></div>
</body>
<script type='text/babel'>

    const container = document.getElementById('main');
    var doc;
    var root;
    var id;

        function setid(){
            id = document.getElementById('id-inp').value;
            document.getElementById('setid').innerHTML = 'id set to '+ id + '<br>';
            Load(id);
        }

        function send(){
            var doc = document.createElement('span')
            var root = ReactDOM.createRoot(doc)
            let messageDiv = document.getElementById('inp');
            root.render(<RenderElement url={'?id='+id+'&data=' + messageDiv.value} />)
            // container.appendChild(document.createElement('span').innerHTML='you:')
            container.appendChild(doc)
            messageDiv.value = ''
        }

        function RenderElement(props){
            console.log(props)
            const [data, setdata] = React.useState(null)
            React.useEffect(() => {
                    fetch('/server.php' + props.url)
                .then(
                    response => response.text()
                )
                .then(
                    responseData => {
                        // console.log(responseData)
                        setdata(responseData)
                    }
                )
            }, []);
            return <p>{data}</p>
        }

        // key represent own's id
        async function Load(key) {
            let response = await fetch('server.php?id='+key);

            if (response.status == 502) {
            // Connection timeout
            // happens when the connection was pending for too long
            // let's reconnect
            await subscribe();
            } else if (response.status != 200) {
            // Show Error
            showMessage(response.statusText);
            // Reconnect in one second
            await new Promise(resolve => setTimeout(resolve, 1000));
            await subscribe();
            } else {
            // Got message
            let message = await response.text();
            if(message != ''){
                var doc = document.createElement('span')
                var root = ReactDOM.createRoot(doc)
                // container.appendChild(document.createElement('span').innerHTML='other:')
                root.render(<p>{message}</p>)
                container.appendChild(doc)
            }
            
            await Load(key);
            
            }
        }
    </script>
</html>
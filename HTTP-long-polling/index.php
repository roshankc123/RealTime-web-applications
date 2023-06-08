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
    <!-- <div id="chata">
        <input type="text" id='ipa'>
        <button onclick="senda()">send</button>
        <div id="maina"></div>
    </div>
    <div id="chatb">
        <input type="text" id='ipb'>
        <button onclick="sendb()">send</button>
        <div id="mainb"></div>
    </div> -->
    <div id="main"></div>
</body>
<script type='text/babel'>

    const container = document.getElementById('main');
    const containerb = document.getElementById('mainb');
    var doc;
    var root;
    // const root = ReactDOM.createRoot(container);
        // function senda(){
        //     var doc = document.createElement('span')
        //     var root = ReactDOM.createRoot(doc)
        //     root.render(<Hello url={'?key=a&data=' + document.getElementById('ipa').value} />)
        //     containera.appendChild(doc)
        // }
        // function sendb(){
        //     var doc = document.createElement('span')
        //     var root = ReactDOM.createRoot(doc)
        //     root.render(<Hello url={'?key=b&data=' + document.getElementById('ipa').value} />)
        //     containerb.appendChild(doc)
        // }
        function Hello(props){
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
        // function Load(iKey){
        //     var doc = document.createElement('span')
        //     var root = ReactDOM.createRoot(doc)
        //     root.render(<Hello iKey={iKey}/>)
        //     container.appendChild(doc)
        // }
        // function onload(){
        //     setInterval(() => {
        //         Load()
        //     }, 2000);
        // }
        async function Load() {
            let response = await fetch('server.php?key=a');

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
                root.render(<p>{message}</p>)
                container.appendChild(doc)
            }
            
            await Load();
            
            }
        }
        // subscribe()
    </script>
</html>
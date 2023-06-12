<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>websockets</title>
    <script src="https://unpkg.com/react@18/umd/react.development.js" crossorigin></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js" crossorigin></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
</head>
<body onload="connect()">
    <input type="text" id="message">
    <button onclick="send()" id='send-btn' disabled>send</button>
    <div id="main"></div>
</body>
<script type='text/babel'>

    const container = document.getElementById('main');
    var wsock;

        function connect(){
            ////tested across network devices(like mobile and a laptop in a same network)
            wsock = new WebSocket('ws://192.168.1.87:6317');
            wsock.onopen = () => {
                RenderElement({'sender':'','message':'connection successfull'})
                document.getElementById('send-btn').disabled = null;
            }
            wsock.onmessage = (message) => {
                console.log(message)
                let messageData = message.data
                let messageObj = JSON.parse(messageData)
                RenderElement(messageObj);
            }
        }


        function send(){
            let messageDiv = document.getElementById('message');
            wsock.send(messageDiv.value);
            messageDiv.value = '';
        }


        function GetMessage(props){
            return <span>
                <p><strong>{props.messageObj.sender}</strong> : {props.messageObj.message}</p>
            </span>
        }


        function RenderElement(message){
            let doc = document.createElement('span')
            let root = ReactDOM.createRoot(doc)
            root.render(<GetMessage messageObj={message}/>)
            container.appendChild(doc)
        }

       
    </script>

</html>
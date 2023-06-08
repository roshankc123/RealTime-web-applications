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
<body onload="onload()">
    <div id="main"></div>
</body>
<script type='text/babel'>

    const container = document.getElementById('main');
    var doc;
    var root;
    // const root = ReactDOM.createRoot(container);
        function Hello(){
            const [data, setdata] = React.useState('hello')
            React.useEffect(() => {
                    fetch('/server.php')
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
            return <h2>{data}</h2>
        }
        function Load(){
            var doc = document.createElement('span')
            var root = ReactDOM.createRoot(doc)
            root.render(<Hello />)
            container.appendChild(doc)
        }
        function onload(){
            setInterval(() => {
                Load()
            }, 2000);
        }
    </script>
</html>
<?php
	include_once('dbconnection.php');
	$dataPoints = array();
	if(isset($_GET['graphType'])) {
		$coin = $_GET['graphType'];
	} else {
		$coin = "btc";
	}
	$query = $conn->prepare("SELECT price, date FROM prices WHERE coin = :coin");
	$query->bindParam(":coin", $_GET['graphType']);
	$query->execute();
	while($row = $query->fetch()) {
		array_push($dataPoints, array("x" => $row['date'], "y" => $row['price']));
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Crypto</title>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/cryptocoins.css">
	<script src="https://use.fontawesome.com/e05cabc499.js"></script>
	<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	<script src="js/javascript.js"></script>
	<script type="text/javascript">
	<?php
		$query = $conn->prepare("SELECT price, date FROM prices WHERE coin = :coin ORDER BY date DESC LIMIT 1");
		$query->bindParam(":coin", $_GET['graphType']);
		$query->execute();
		$row = $query->fetch();
	?>
	$(function () {
	var chart = new CanvasJS.Chart("chartContainer", {
		theme: "theme2",
		zoomEnabled: true,
		animationEnabled: true,
		title: {
			text: "$<?php echo $row['price'] ?>"
		},
		data: [
		{
			xValueType: "dateTime",
			xValueFormatString:"MMM DD YYYY hh:mm TT",
			yValueFormatString: "$######.00",
			color: "#1c5125",
			type: "line",                
			dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
		}
		]
	});
	chart.render();
	});
</script>
</head>
<body>
	<div class="login-bar">
		<div class="login-bar-container"></div>
	</div>
	<div class="navbar">
		<a class="navbar-link active-link" href="index.php"><div class="navbar-link-container"><i class="fa fa-line-chart" aria-hidden="true"></i> Graphs</div></a>
		<a class="navbar-link" href="#"><div class="navbar-link-container"><i class="fa fa-rss" aria-hidden="true"></i> Track</div></a>
	</div>
	<div class="graph-menu-container">
		<div class="graph-choice-container">
			<a href="index.php?graphType=btc" id="btc-graph" class="graph-choice-link"><div class="graph-choice"><i class="cc BTC" title="BTC"></i> Bitcoin</div></a>
			<a href="index.php?graphType=ltc" id="ltc-graph" class="graph-choice-link"><div class="graph-choice"><i class="cc LTC" title="LTC"></i> Litecoin</div></a>
			<a href="index.php?graphType=eth" id="eth-graph" class="graph-choice-link"><div class="graph-choice"><i class="cc ETH" title="ETH"></i> Ether</div></a>
			<a href="index.php?graphType=xrp" id="xrp-graph" class="graph-choice-link"><div class="graph-choice"><i class="cc XRP" title="XRP"></i> Ripple</div></a>
		</div>
		<div id="chartContainer"></div>
	</div>
	<script type="text/javascript">
		if(findGetParameter("graphType") == "ltc") {
			document.getElementById("ltc-graph").className += " active-link";
		} else if(findGetParameter("graphType") == "eth") {
			document.getElementById("eth-graph").className += " active-link";
		} else if(findGetParameter("graphType") == "xrp") {
			document.getElementById("xrp-graph").className += " active-link";
		} else {
			document.getElementById("btc-graph").className += " active-link";
		}
	</script>
</body>
</html>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Apacheログ解析</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

	<link rel="stylesheet" href="stylesheet.css">
	<?php require "function.php" ?>
</head>

<body>
	<div class="" style="padding-bottom: 100px; height: 100%">
		<div class="container">
			<nav class="navbar navbar-inverse">
				<div class="navbar-header">
					<button class="navbar-toggle" data-toggle="collapse" data-target=".target">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
					<a class="navbar-brand" href="http://localhost/php/ApacheLogAnalysis/">Apacheログ解析</a>
				</div>
				<div class="collapse navbar-collapse target">
					<ul class="nav navbar-nav">
					</ul>
					<ul class="nav navbar-nav navbar-right">
					</ul>
				</div>
			</nav>
			<?php
				$logAnalyzer = new LogAnalyzer();
				$begin = $_GET['begin-Y'].'/'.$_GET['begin-M'].'/'.$_GET['begin-D'];
				$end = $_GET['end-Y'].'/'.$_GET['end-M'].'/'.$_GET['end-D'];
				$array = $logAnalyzer->analyze($begin, $end);
			?>
			<div class="row" style="padding-right: 30px; padding-left: 30px">
				<p>
					<?php
						echo $_GET['begin-Y'].'年'.$_GET['begin-M'].'月'.$_GET['begin-D'].'日から';
						echo $_GET['end-Y'].'年'.$_GET['end-M'].'月'.$_GET['end-D'].'日で絞り込んだ結果';
					?>
				</p>
			</div>
			<div class="row" style="padding-right: 30px; padding-left: 30px">
				<p>
					各時間帯毎のアクセス数
					<table class="table table-hover text-center table-bordered" style="width: 500px">
						<thead class="text-center">
							<tr>
								<th class="t-text">0-6</th>
								<th class="t-text">6-12</th>
								<th class="t-text">12-18</th>
								<th class="t-text">18-24</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php echo $array['timeZone']['0-6'] ?></td>
								<td><?php echo $array['timeZone']['6-12'] ?></td>
								<td><?php echo $array['timeZone']['12-18'] ?></td>
								<td><?php echo $array['timeZone']['18-24'] ?></td>
							</tr>
						</tbody>
					</table>
				</p>
			</div>
			<div class="row" style="padding-right: 30px; padding-left: 30px">
				<p>
					リモートホスト別のアクセス件数
					<table class="table table-hover text-center table-striped table-bordered" style="width: 500px">
						<thead class="text-center">
							<tr>
								<th style="width: 30px; text-align: center">#</th>
								<th class="t-text">リモートホスト</th>
								<th class="t-text">件数</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$cnt = 1;
								foreach ($array['remoteHost'] as $key => $value) {
									echo "<tr>";
									echo "<td>".$cnt."</td>";
									echo "<td>".$key."</td>";
									echo "<td>".$value."</td>";
									echo "</tr>";
									$cnt++;
								}
							?>
						</tbody>
					</table>
				</p>
			</div>
			<div class="row" style="padding-right: 30px; padding-left: 30px">
				<form class="form-horizontal" action="#" method="get">
					<div class="form-inline">
						<div class="form-group" style="width: 150px">
							<select class="form-control" name="begin-Y" style="width: 100px">
								<?php
									for ($i = 1970; $i <= date("Y", strtotime("now")); $i++) {
										echo "<option>".$i."</option>\n";
									}
								?>
							</select>
							<label>年</label>
						</div>
						<div class="form-group" style="width: 150px">
							<select class="form-control" name="begin-M" style="width: 100px">
								<?php
									for ($i = 1; $i <= 12; $i++) {
										echo "<option>".$i."</option>\n";
									}
								?>
							</select>
							<label>月</label>
						</div>
						<div class="form-group" style="width: 175px">
							<select class="form-control" name="begin-D" style="width: 100px">
								<?php
									for ($i = 1; $i <= 31; $i++) {
										echo "<option>".$i."</option>\n";
									}
								?>
							</select>
							<label>日から</label>
						</div>
						<div class="form-group" style="width: 150px">
							<select class="form-control" name="end-Y" style="width: 100px">
								<?php
									for ($i = 1970; $i <= date("Y", strtotime("now")); $i++) {
										echo "<option>".$i."</option>\n";
									}
								?>
							</select>
							<label>年</label>
						</div>
						<div class="form-group" style="width: 150px">
							<select class="form-control" name="end-M" style="width: 100px">
								<?php
								for ($i = 1; $i <= 12; $i++) {
									echo "<option>".$i."</option>\n";
								}
							?>
							</select>
							<label>月</label>
						</div>
						<div class="form-group" style="width: 165px">
							<select class="form-control" name="end-D" style="width: 100px">
								<?php
									for ($i = 1; $i <= 31; $i++) {
										echo "<option>".$i."</option>\n";
									}
								?>
							</select>
							<label>日で</label>
						</div>
						<div class="form-group">
							<button class="btn btn-primary" type="submit">絞り込み</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>

</html>

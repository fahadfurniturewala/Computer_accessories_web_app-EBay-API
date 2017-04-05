<!--Name: Fahad Furniturewala-->
<?php
session_start();
$total=0;
?>
<html>
<head><title>Buy Products</title></head>
<body bgcolor="#ccffff">
<center>
<p>
BUY ALL THE TECH YOU NEED!
<br>
CART
</p>
</center>
<?php

$noitems=0;
if(empty($_SESSION['cart'])){
	$_SESSION['cart']=array();
}

if(isset($_GET['buy']))
	{
	$productid=$_GET['buy'];
	$productsearch=file_get_contents('http://sandbox.api.shopping.com/publisher/3.0/rest/GeneralSearch?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&visitorUserAgent&visitorIPAddress&trackingId=7000610&productId='.$productid.'');
	$productsearchxml = new SimpleXMLElement($productsearch);
	$final=$productsearchxml->categories->category->items->product;
	$productid1=(string)$final['id'];
	$productname1=(string)$final->name;
	$imgl1=(string)$final->images->image[0]->sourceURL;
	$productprice1=(string)$final->minPrice;
	$producturl=(string)$final->productOffersURL;
	$cartarray=array();
	array_push($cartarray,$productid1);
	array_push($cartarray,$productname1);
	array_push($cartarray,$imgl1);
	array_push($cartarray,$productprice1);
	array_push($cartarray,$producturl);
	$_SESSION['cart'][$productid1]=$cartarray;
	}

if(isset($_GET['delete']))
{
	$remove=$_GET['delete'];
	unset($_SESSION['cart'][$remove]);
}

if(isset($_GET['clear']))
{
		session_unset();
}

echo '<center>';
echo '<table border="2">';


	if(!empty($_SESSION['cart']))
	{
		if($noitems==0)
		{
			echo '<th>',"Product",'</th>';
			echo '<th>',"Product Image",'</th>';
			echo '<th>',"Price",'</th>';
			echo '<th>',"",'</th>';
			$noitems=1;
		}
		foreach($_SESSION['cart'] as $value)
		{
	
		echo '<tr>';	
		$total=$total+$value[3];
		echo '<td>',$value[1],'</td>';	
		echo '<td>','<a href='.$value[4].'>','<img src='.$value[2].'>','</a>','</td>';	
		echo '<td>',"$",$value[3],'</td>';	
		echo '<td>','<form>','<a href=buy.php?delete='.$value[0].'>','<input type=button value="Delete from Cart">','</a>','</form>','</td>';
		echo '</tr>';
		}
	}
echo '</table>';
echo '</center>';
echo '<form>';
echo '<a href="buy.php?clear=1">';
echo '<input type=button value="EMPTY BASKET">';
echo '</a>';
echo '</form>'; 
echo "TOTAL:$",$total;

?>

<?php
echo '<center>';
echo '<form method="get" action="buy.php" name="input">';
echo '<select id="cate" name="cate">';
error_reporting(E_ALL);
ini_set('display_errors','On');
$xmlstr = file_get_contents('http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/CategoryTree?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&visitorUserAgent&visitorIPAddress&trackingId=7000610&categoryId=72&showAllDescendants=true');
$xml = new SimpleXMLElement($xmlstr);
header('Content-Type: text/html');
//print $xmlstr;
$x=count($xml->category[0]->categories->category);
//print $x;
//print '<br>';
echo '<b>',"<option value=72>Computers</option>",'<b>';
	
	foreach($xml->category->categories->category as $subcat)
	{
		$id=$subcat['id'];
		echo "<option value='$id'>$subcat->name</option>";
		
		foreach($subcat->categories->category as $subcat2)
		{
			$id2=$subcat2['id'];
		echo "<optgroup>";
		echo "<option value='$id2'>$subcat2->name</option>";
		echo "</optgroup>";
		}
	
		
		
	}
	
	echo '</select>';
	
/*if(!function_exists('extract')){
function extract()
{
$s=$_POST['search'];
print '<br>';
print $s;
}
}*/

echo '&nbsp';
echo '&nbsp';
echo '&nbsp';
echo '&nbsp';
echo '<input type="text" id="search" name="search">';
echo '&nbsp';
echo '&nbsp';
echo '&nbsp';
echo '&nbsp';
echo '<input type="submit" id="find" value="Find">';
echo '</form>';
echo '</center>';

if(isset($_GET['search']))
{
$s1=$_GET['search'];
$s=str_replace(" ","+",$s1);
$c=$_GET['cate'];
echo '<br>';
$results = file_get_contents('http://sandbox.api.shopping.com/publisher/3.0/rest/GeneralSearch?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&visitorUserAgent&visitorIPAddress&trackingId=7000610&categoryId='.$c.'&keyword='.$s.'&numItems=20');
$results2 = new SimpleXMLElement($results);
//print $results2;

//$results = file_get_contents('http://sandbox.api.shopping.com/publisher/3.0/rest/GeneralSearch?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&visitorUserAgent&visitorIPAddress&trackingId=7000610&categoryId=72&keyword=amd&numItems=20');
//$results2 = new SimpleXMLElement($results);
//print $results;
echo '<center>';
echo '<table>';
echo '<tr>';
echo '<th>', "Product", '</th>';
echo '<th>', "Image", '</th>';
echo '<th>', "Description", '</th>';
echo '<th>', "Price", '</th>';
echo '<th>', "Cart", '</th>';
echo '</tr>';
foreach($results2->categories->category->items->product as $prodname)
{
	echo '<tr>';
	$productname=$prodname->name;
	$productid=$prodname['id'];
	echo '<td>',$productname,'</td>';
	$imgl=$prodname->images->image[0]->sourceURL;
	echo '<td>','<img src='.$imgl.'></a>','</td>';
	//$pdesc=$prodname->fullDescription;
	echo '<td>',$prodname->fullDescription,'</td>';
	$productprice=$prodname->minPrice;
	echo '<td>',"$",$productprice,'</td>';
	echo '<td>','<form>','<a href=buy.php?buy='.$productid.'>','<input type=button value="Add to Cart">','</a>','</form>','</td>';
	
	//echo '<td>','<form>','<input type=button value="Add To cart" onClick="localhost/project3/buy.php?buy='.$productid.'" >','</form>','</td>';
	echo '</tr>';
	
}
echo '</table>';
echo '</center>';


	}
	
?>
</body>
</html>




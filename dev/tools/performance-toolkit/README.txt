Performance Toolkit
=============

Installation
-----------
jMeter:
-- go to http://jmeter.apache.org/download_jmeter.cgi and download jMeter in Binary section (pay you attention that Java 6 or later is required)
-- unzip archive

Plugins (only if you want to use additional reports (like graphs)):
-- go to http://jmeter-plugins.org/downloads/all/ and download JMeterPlugins-Standard and JMeterPlugins-Extras
-- unzip them to appropriate ext directory of your jMeter instance.
-- additional reports can now be enabled via GUI (right click them, then click Enable) or editing the jMeter JMX XML (set 'enabled="true"').


Usage
-----------
Before running the jMeter tests for the first time, you will need to first use generate.php to generate the test data.

If you do not wish to use performance metrics gathered from the server(s) (such as CPU / IO load), or do not wish to configure the jMeter metric gathering software on your server(s), you can disable that either via the GUI (right click on "Performance Metrics Collector" and click Disable) or by editing the jMeter JMX XML from:
<kg.apc.jmeter.perfmon.PerfMonCollector guiclass="kg.apc.jmeter.vizualizers.PerfMonGui" testclass="kg.apc.jmeter.perfmon.PerfMonCollector" testname="Performance Metrics Collector" enabled="true">
to:
<kg.apc.jmeter.perfmon.PerfMonCollector guiclass="kg.apc.jmeter.vizualizers.PerfMonGui" testclass="kg.apc.jmeter.perfmon.PerfMonCollector" testname="Performance Metrics Collector" enabled="false">
Attempting to run with Performance Metrics Collector enabled without appropriately configuring it will result in a timeout error connecting to the collector and the test not running.

1. Run via console
Scenario can accept parameters that are described bellow in format <parameter_name:default_value>:

<host:''> URL component 'host' of application being tested (URL or IP).
<base_path:'/'> Base path for tested site.
<users:100> Number of concurrent users. Recommended  amount is 100. Minimal amount is 10.
<ramp_period:300> Ramp period (seconds). Period the request will be distributed within.
<orders:0> Number of orders in the period specified in the current allocation. If <orders> is specified, the <users> parameter will be recalculated.
<report_save_path:./> Path where reports will be saved. Reports will be saved in current working directory by default.
<admin_path:backend/> Admin backend path. Default is backend.
<admin_user:admin/> Admin backend user. Default is admin.
<admin_password:123123q/> Admin backend password. Default is 123123q.
<view_product_add_to_cart_percent:62/> Percentage of users that will only reach the add to cart stage. Default is 62.
<view_catalog_percent:30/> Percentage of users that will only reach the view catalog stage. Default is 30.
<guest_checkout_percent:4/> Percentage of users that will reach the guest checkout stage. Default is 4.
<customer_checkout_percent:4/> Percentage of users that will reach the (logged-in) customer checkout stage. Default is 4.
<loops:1/> Number of loops to run. Default is 1.
<jmeter_agent_port:3450/> jMeter agent port. Default is 3450.
<db_server_ip:10.62.51.150/> DB Server IP (for jMeter Performance Metrics). Default is 10.62.51.150.
<web_server_1_ip:10.62.51.185/> Web Server 1 IP (for jMeter Performance Metrics). Default is 10.62.51.185.
<web_server_2_ip:10.62.51.186/> Web Server 2 IP (for jMeter Performance Metrics). Default is 10.62.51.186.

All parameters must be passed to command line with "J" prefix: "-J<parameter_name>=<parameter_value>"

Example:
> cd /directory_of_jMeter/bin/
> jmeter -n -t /path_to_benchmark_file/benchmark.jmx -Jhost=magento2.dev -Jbase_path=/ -Jusers=100 -Jramp_period=300 -Jreport_save_path=./


2. Run via GUI
-- Open jMeter/bin directory and run jmeter.bat
-- Click in menu File -> Open (Ctrl+O) and select file; or drag and drop benchmark.jmx file in opened GUI.

On the first tab 'Test Toolkit' you can change 'User Defined variables' like as <host>, <users>, <ramp_period>, <orders>, <report_save_path>.
For running script click "Start" (green arrow in the top menu).


Results of running (Report types)
-----------

After running via GUI you can see result of working in left panel. Choose the corresponding report.
After running script via console report will be generated in the path that  has been specified in <report_save_path>.


Threads
-----------

jMeter script consists of five threads. Setup thread and four user threads.
Percentage ratio between threads is as follows:
Browsing, adding items to the cart and abandon cart (BrowsAddToCart suffix in reports) - 62%
Just browsing (BrowsAddToCart suffix in reports) - 30%
Browsing, adding items to cart and checkout it as guest (GuestChkt suffix in reports) -  4%
Browsing, adding items to cart and checkout as registered customer (CustomerChkt suffix in reports) - 4%


About reports:
-----------

Summary Report.
Report contains aggregated information about threads.
Report file name is {report_save_path}/summary-report.log
Details http://jmeter.apache.org/usermanual/component_reference.html#Summary_Report

Detailed URLs report.
Report contains information about URLs.
Pay your attention that URL is displayed only in generated report file (in GUI, URL is not displayed).
Report file name is {report_save_path}/detailed-urls-report.log (can be open as csv format).
Details http://jmeter.apache.org/usermanual/component_reference.html#View_Results_in_Table

About other types read on
http://jmeter.apache.org/usermanual/component_reference.html


Testing environment
-----------

jMeter: apache-jmeter-2.11
OS (where jMeter is running): Windows 7 SP1
Server (where Magento is hosted): Intel(R) Core(TM)2 Duo CPU T7700  @2.40GHz, memtotal 4gb.
PHP:  5.4.19 (memory_limit 2Gb)
MySQL: 5.5.29 MySQL Community Server
Magento version:  ver. 2.0.0.0-dev70 (rev 16b68a0f8e0fad4375f33b7238e2f2964ac3aadc)
Magento database (Small plan in Fixture Generation Tool):
jMeter parameters (all default parameters):
  users: 100
  ramp_period: 300
  view_product_add_to_cart_percent: 62
  view_catalog_percent: 30
  guest_checkout_percent: 4
  customer_checkout_percent: 4

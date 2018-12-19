# Forecastr
Forecastr Files
  Broker -->
  brokerStartupBackup.service --> automatic systemd unit files
  databaseStartupBackup.service
  networkLoggerListener.php --> Listens and Logs communication over RabbitMQ
  networkLoggerListenerBackup.php

  Client-->
  register.html --> User Interface Files
  register.php
  homepage2.php
  search.php
  save.php
  logout.php
  login.php
  weatherClient.php --> Library used to communicate with DMZ
  testRabbitMQClient.php --> Used to login user and communicate with DB

  DMZ -->
  dmzStartup.service --> automatic systemd unit files used to trigger DMZ Listener
  dmzStartupBackup.service
  networkLogger.php --> reports to networkLoggerListener.php over rabbitMQ when communicating
  weatherServer.php --> Communicates with Open-Weather-Map-API to get weather information

  Database -->
  databaseStartup.service --> automatic systemd unit files to trigger DB Listener
  databaseStartupBackup.service
  HotStandby.php --> Backup database server deployed if primary goes down
  testRabbitMQServer.php --> Listens and processes DB requests from rabbitMQ

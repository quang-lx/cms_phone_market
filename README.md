 

## Install elasticsearch V7.3.1
```
wget https://artifacts.elastic.co/downloads/elasticsearch/elasticsearch-7.3.1-amd64.deb
wget https://artifacts.elastic.co/downloads/elasticsearch/elasticsearch-7.3.1-amd64.deb.sha512
shasum -a 512 -c elasticsearch-7.3.1-amd64.deb.sha512 
sudo dpkg -i elasticsearch-7.3.1-amd64.deb

```
 ### start/stop ES 
 SysV init vs systemdedit
 Elasticsearch is not started automatically after installation. How to start and stop Elasticsearch depends on whether your system uses SysV init or systemd (used by newer distributions). You can tell which is being used by running this command:
 
```
 ps -p 1
```
 
 Running Elasticsearch with systemd 
 To configure Elasticsearch to start automatically when the system boots up, run the following commands:
 
```
 sudo /bin/systemctl daemon-reload
 sudo /bin/systemctl enable elasticsearch.service
```
 Elasticsearch can be started and stopped as follows:
 
```
 sudo systemctl start elasticsearch.service
 sudo systemctl stop elasticsearch.service
```
### create audio vov
```
 nohup ffmpeg -i http://cdn.mediatech.vn/hntvRadio/joyfm.stream_aac/playlist.m3u8 -f segment -c copy -segment_time 10 -acodec libmp3lame /data/audio/_vov%03d.mp3 &
 ---30383
 
 export GOOGLE_APPLICATION_CREDENTIALS="/var/www/mylane_web/google_cloud_cre.json"

 nohup   php /var/www/mylane_web/artisan gg-cloud:stt-vov &
--- 1693
```

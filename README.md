TOPIC: Head First PHP framework architecture
+ Configuration
<pre><code>
    \<VirtualHost *:80\>
        ServerName norspl.github.com
        DocumentRoot "/opt/www/fmphp/norspl"
        ErrorLog "logs/norspl.github.com-error.log"
        CustomLog "logs/norspl.github.com-access.log" common
        \<Directory "/opt/www/fmphp/norspl"\>
        	AllowOverride None
      		Options Includes FollowSymLinks Indexes
      		Order allow,deny
      		Allow from all
        \</Directory\>
    \</VirtualHost\>
</code></pre>

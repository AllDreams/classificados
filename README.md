# classificados
Componente Joomla de Classificados, para gerenciar classificados tipo , imoveis, comida, etc.


Criação de banco para Joomla recomendado os comandos:
<code>
create database joomla;<br/>
create user joomla@'127.0.0.1' identified by 'joomla';<br/>
grant all on joomla.* to joomla@'127.0.0.1';<br/>
</code>


Adapidato para versão 4 do Joomla 


Montando a pasta
<code>
cd /var/www/html/joomla/components/
sudo rm -Rf com_classificados/
sudo ln -s ~/Documents/git/classificados/components/com_classificados/ com_classificados
cd /var/www/html/joomla/administrator/components/
sudo rm -Rf com_classificados/
sudo ln -s ~/Documents/git/classificados/administrator/components/com_classificados/ com_classificados
cd /var/www/html/joomla/media/
sudo rm -Rf com_classificados/
sudo ln -s ~/Documents/git/classificados/media/com_classificados/ com_classificados
cd /var/www/html/joomla/language/en-GB/
sudo ln -s ~/Documents/git/classificados/components/com_classificados/language/pt-BR/pt-BR.com_classificados.ini ../pt-BR/com_classificados.ini
sudo ln -s ~/Documents/git/classificados/components/com_classificados/language/en-GB/en-GB.com_classificados.ini ../en-GB/com_classificados.ini
cd /var/www/html/joomla/administrator/language/en-GB/
sudo ln -s ~/Documents/git/classificados/administrator/components/com_classificados/language/pt-BR/pt-BR.com_classificados.ini ../pt-BR/com_classificados.ini
sudo ln -s ~/Documents/git/classificados/administrator/components/com_classificados/language/pt-BR/pt-BR.com_classificados.sys.ini ../pt-BR/com_classificados.sys.ini
sudo ln -s ~/Documents/git/classificados/administrator/components/com_classificados/language/en-GB/en-GB.com_classificados.ini ../en-GB/com_classificados.ini
sudo ln -s ~/Documents/git/classificados/administrator/components/com_classificados/language/en-GB/en-GB.com_classificados.sys.ini ../en-GB/com_classificados.sys.ini
</code>
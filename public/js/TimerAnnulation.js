function startAutoRefresh()
{
   //alert('Autorefresh est on');
   setInterval(function(){startSync()},5000);
}

function startSync()
{
   //alert('reload de la page');
   monUrl = 'commande_historique';
   window.location.href=monUrl;
}
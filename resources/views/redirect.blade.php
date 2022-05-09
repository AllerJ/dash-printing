<script>
	$( document ).ready(function() {
		Lit.bus.$on('reloaded', (value) => {
			window.location.replace("{{ $url }}");
		});		
	});			
</script>

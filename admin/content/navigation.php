<style>
    .navigation{
    background: lightblue;
    padding: 9px 0px;
    display: flex;
    justify-content: center;
    }
    
    .navigation-link{
    color: whitesmoke;
    padding: 0px 12px;
    font-size: 2em;
    background-color: darkcyan;
    border-radius: 10px;
    margin: 0px 10px;
    }
    
    
@media only screen and (max-width: 768px) {
  /* For mobile phones: */
    [class*="navigation-link"] {
    padding: 0px 5px;
    margin: 2px 10px;
    font-size: 15px;
    border-radius: 5px;
    }
    [class*="navigation"] {
    padding: 2px 2px;
    font-size: 17px;
    }
}
</style>

<div class="navigation row">
    <a class = "navigation-link col" href="?page=edituser">
    Käyttäjät</a>
    <a class = "navigation-link col" href="?page=editteam">
    Tapahtumat</a>
    <a class = "navigation-link col" href="?page=editregions">
    Alueet</a>
    <a class = "navigation-link col" href="?page=teamData">
    Tilastot</a>
    <a class = "navigation-link col" href="?page=metaData">
    Taulukot</a>
</div>
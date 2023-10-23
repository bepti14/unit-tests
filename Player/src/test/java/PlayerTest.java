import org.junit.jupiter.api.AfterEach;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.DisplayName;
import org.junit.jupiter.api.Test;

import static org.junit.jupiter.api.Assertions.*;

class PlayerTest {

    Player player;

    @BeforeEach
    void setUp() {
        player = new Player();
    }

    @AfterEach
    void tearDown() {
    }

    @Test
    @DisplayName("Utworz gracza")
    void createPlayer() {
        assertEquals(true, player.createPlayer("Maniek", 2003, true));
        assertEquals(false, player.createPlayer("Maniek@!>", 2006, false));
    }

    @Test
    @DisplayName("Ustaw nazwe")
    void setName() {
        player.setName("Pawel#$#@");
        assertNull(player.getName());
        player.setName("Pawel");
        assertEquals("Pawel", player.getName());
    }

    @Test
    @DisplayName("Sprawdz wiek")
    void setBonYear() {
        assertEquals(true, player.setBornYear(2003));
        assertEquals(false, player.setBornYear(2017));
    }
}
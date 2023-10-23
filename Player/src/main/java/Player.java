import java.time.Year;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class Player {
    private String name;
    private Integer bornYear;
    private Boolean agree;

    public Boolean createPlayer(String name, Integer bornYear, Boolean agree) {
        setName(name);

        if(name != null && agree == true && setBornYear(bornYear) == true) {
            this.agree = agree;
            return true;
        }

        return false;
    }

    public void setName(String name) {
        String validPattern = "^[a-zA-Z0-9_]+$";

        Pattern pattern = Pattern.compile(validPattern);
        Matcher matcher = pattern.matcher(name);

        if(matcher.matches()) this.name = name;
        else this.name = null;
    }

    public String getName() {
        return name;
    }

    public Boolean setBornYear(Integer born) {
        int year = Year.now().getValue();

        if(year - born >= 18) {
            bornYear = year - born;
            return true;
        }

        return false;
    }

}

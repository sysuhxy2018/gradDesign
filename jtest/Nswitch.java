import java.util.*;

public class Nswitch{
    public static void main(String[] args){
        Scanner input = new Scanner(System.in);
        int day = input.nextInt();
        switch (day){
            case 1:
            case 2:
            case 3:
            case 4:
            case 5: System.out.println("Weekday"); break;
            case 6:
            case 7: System.out.println("Weekend"); break;
            default: System.out.println("Invalid"); break;
        }
    }
}
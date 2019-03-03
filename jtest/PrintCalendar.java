import java.util.Scanner;

public class PrintCalendar{
    public static void main(String[] args){
        Scanner input = new Scanner(System.in);
        int year, month;
        System.out.print("Enter full year (e.g., 2012): ");
        year = input.nextInt();
        System.out.print("Enter month as number between 1 and 12: ");
        month = input.nextInt();
        printMonth(year, month);
    }

    public static void printMonth(int year, int month){
        printMonthTitle(year, month);
        printMonthBody(year, month);
    }

    public static void printMonthTitle(int year, int month){
        System.out.print("\n        " + getMonthName(month) +
        " " + year + "\n\n-----------------------------\n\n" +
        " Sun Mon Tue Wed Thu Fri Sat \n\n");
    }

    public static String getMonthName(int month){
        String monthName = "";
        switch (month) {
            case 1: monthName = "January"; break;
            case 2: monthName = "Febuary"; break;
            case 3: monthName = "March"; break;
            case 4: monthName = "April"; break;
            case 5: monthName = "May"; break;
            case 6: monthName = "June"; break;
            case 7: monthName = "July"; break;
            case 8: monthName = "June"; break;
            case 9: monthName = "September"; break;
            case 10: monthName = "October"; break;
            case 11: monthName = "November"; break;
            case 12: monthName = "December"; break;
        }
        return monthName;
    }

    public static void printMonthBody(int year, int month){
        int startDay = getStartDay();
        int totalNumberOfDays = getTotalNumberOfDays(year, month);
        int firstDayOfMonth = (startDay + totalNumberOfDays) % 7;
        int numberOfDaysInMonth = getNumberOfDaysInMonth(year, month);
        for (int i = 0; i < firstDayOfMonth; i++){
            System.out.print("    ");
        }
        for (int i = 1; i <= numberOfDaysInMonth; i++){
            System.out.printf("%4d", i);
            if((i + firstDayOfMonth) % 7 == 0 || i == numberOfDaysInMonth)
                System.out.print("\n\n");
        }
    }

    public static int getStartDay(){
        final int START_DAY_FOR_JAN_1_1800 = 3;
        return START_DAY_FOR_JAN_1_1800;
    }

    public static int getTotalNumberOfDays(int year, int month){
        int daysInYears = 0;
        int daysInAYear = 0;
        final int START_YEAR = 1800;
        for (int i = START_YEAR; i < year; i++){
            if(isLeapYear(i))
                daysInYears += 366;
            else
                daysInYears += 365;
        }
        for (int i = 1; i < month; i++){
            daysInAYear += getNumberOfDaysInMonth(year, i);
        }
        return daysInYears + daysInAYear;
    }

    public static boolean isLeapYear(int year){
        return (year % 4 == 0 && year % 100 != 0) || year % 400 == 0;
    }

    public static int getNumberOfDaysInMonth(int year, int month){
        int days = 0;
        switch (month) {
            case 1: days = 31; break;
            case 2: days = isLeapYear(year) ? 29 : 28; break;
            case 3: days = 31; break;
            case 4: days = 30; break;
            case 5: days = 31; break;
            case 6: days = 30; break;
            case 7: days = 31; break;
            case 8: days = 31; break;
            case 9: days = 30; break;
            case 10: days = 31; break;
            case 11: days = 30; break;
            case 12: days = 31; break;
        }
        return days;
    }
}
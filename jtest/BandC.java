public class BandC{
    public static void main(String[] args){
        System.out.println("Example 1");
        int i, j;
        for(i = 0; i < 10; i++){
            if(i == 2)
                continue;
            if(i == 7)
                break;
            System.out.println(i);
        }
        System.out.println(i);
        System.out.println("Example 2");
        for (int x = 1; x < 4; x++) {
            for (int y = 1; y < 4; y++) {
                if (x * y > 2)
                    break;
                System.out.println(x * y);
            }
            System.out.println(x);
        }
        System.out.println("Example 3");
        for (int x = 1; x < 4; x++) {
            for (int y = 1; y < 4; y++) {
                if (x * y > 2)
                    continue;
                System.out.println(x * y);
            }
            System.out.println(x);
        }
    }
}

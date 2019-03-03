/**
 * 一种暴力枚举得到8皇后解的算法
 * 注意每次枚举不一定成功放置8皇后，可能不够8个，需要进行下一次枚举
 * 只用到了一维数组，二维可能更方便，不过一维和二维都可以互相转换的
 */

public class EightQueen{
    public static void main(String[] args) {
        boolean[] queen = getQueen();
        while (queen == null) {
            queen = getQueen();
        }
        printQueen(queen);
    }

    public static boolean[] getQueen() {
        boolean[] visit = initialVisit();
        boolean[] queen = initialVisit();
        int count = 0;
        while (canVisit(visit) && count < 8) {
            int pos = getRandomPos(visit);
            /*while (visit[pos]) {
                pos = getRandomPos(visit);
            }*/
            occupyBoard(pos, visit);
            count++;
            queen[pos] = true;
        }
        if (count < 8) {
            System.out.println("failure");
            return null;
        }
        return queen;
    }

    public static int[] initialBoard() {
        int[] board = new int[64];
        return board;
    }

    public static boolean[] initialVisit() {
        boolean[] visit = new boolean[64];
        return visit;
    }

    public static int getRandomPos(boolean[] visit) {
        int[] newBoard = initialBoard();
        int count = 0;
        for (int i = 0; i < visit.length; i++) {
            if (!visit[i]) {
                newBoard[count] = i;
                count++;
            }
        }
        int randomNum = newBoard[(int)(Math.random() * count)];
        return randomNum;
    }

    public static boolean canVisit(boolean[] visit) {
        for (int i = 0; i < visit.length; i++) {
            if (!visit[i]) {
                return true;
            }       
        }
        return false;
    }

    public static void occupyBoard(int pos, boolean[] visit) {
        int row, col;
        row = pos / 8;
        col = pos % 8;
        int[] rowMove = {-1, 0, 1};
        int[] colMove = {-1, 0, 1};
        visit[pos] = true;
        for (int i = 0; i < rowMove.length; i++) {
            for (int j = 0; j < colMove.length; j++) {
                int rowOff = rowMove[i];
                int colOff = colMove[j];
                int x = row, y = col;
                if (rowOff == 0 && colOff == 0)
                    continue;
                while (x >= 0 && x < 8 && y >= 0 && y < 8) {
                    visit[x * 8 + y] = true;
                    x += rowOff;
                    y += colOff;
                }
            }
        }
    }
    
    public static void printQueen(boolean[] queen) {
        for (int i = 0; i < queen.length; i++) {
            if (!queen[i]) {
                System.out.print("| ");
            }
            else{
                System.out.print("|Q");
            }
            if ((i + 1) % 8 == 0) {
                System.out.print("|\n");
            }
        }
    }
}

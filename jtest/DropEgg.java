/**
 * leetcode 887 的弱化版本
 * 只有2个🥚。
 * 思路是将其分成两段来考虑，因为不知道分割点在哪里，
 * 所以需要做一次内部遍历。
 * 每次分割点取最坏的情况，只有两种情况，一个向上找，一个向下找。
 * 最后取最优的那次分割点即可。
 * 
 * 注意此题的关键在于你不能控制🥚碎的楼层（所以要作最坏的打算），
 * 但是你可以控制最好的分割点（最优方案）。
 */

public class DropEgg{
    public static void main(String[] args) {
        int[] opt = new int[1000];
        opt[0] = 0;
        for (int i = 1; i <= 36; i++) {
            int maxx = Integer.MAX_VALUE;
            for (int j = 0; j < i; j++) {
                maxx = Math.min(maxx, Math.max(1 + j, 1 + opt[i - j - 1]));
            }
            opt[i] = maxx;
        }
        System.out.println(opt[36]);
    }
}